<?php

namespace Omadonex\LaravelTools\Support\Transformers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Omadonex\LaravelTools\Acl\Interfaces\IRole;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Acl\Repositories\UserRepository;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Tools\Color;

class HistoryTransformer extends BaseTransformer
{
    private UserRepository $userRepository;
    private bool $isAdmin;

    public function __construct($response, $params = [])
    {
        parent::__construct($response, $params, false);
        $this->userRepository = app(UserRepository::class);
        $this->isAdmin = app('acl')->checkRole(IRole::ADMIN);
    }

    protected function transformers(): array
    {
        return [
            'occur_at' => $this->makeDateTime(),
            'event' => function ($value, $row, $rowOriginal) {
                switch ($row->history_event_id) {
                    case HistoryEvent::CREATE:
                        return getIconHtml('streamline.bold.add-bold', 16, Color::SUCCESS, Color::SUCCESS) . $value;
                    case HistoryEvent::CREATE_T:
                        return getIconHtml('streamline.regular.translate', 16, Color::SUCCESS) . $value;
                    case HistoryEvent::UPDATE:
                        return getIconHtml('streamline.regular.edit', 16, Color::WARNING, Color::WARNING) . $value;
                    case HistoryEvent::UPDATE_T:
                        return getIconHtml('streamline.regular.translate', 16, Color::WARNING) . $value;
                    case HistoryEvent::DELETE:
                        return getIconHtml('streamline.regular.delete', 16, Color::DANGER, Color::DANGER) . $value;
                    case HistoryEvent::DELETE_T:
                    case HistoryEvent::DELETE_T_ALL:
                        return getIconHtml('streamline.regular.translate', 16, Color::DANGER) . $value;
                }

                return $value;
            },
            'user_id' => function ($value, $row, $rowOriginal) {
                if (!$value) {
                    return 'Система';
                }

                $user = $this->userRepository->find($value);
                if (!$this->isAdmin) {
                    return $user->displayName;
                }

                return $this->makeLink(User::getRouteName('show'), $user->displayName)($value, $row, $rowOriginal);
            },
            'model_id' => function ($value, $row, $rowOriginal) {
                if (!array_key_exists('modelShowUrl', $this->params)) {
                    return $value;
                }

                if (in_array($row->history_event_id, [HistoryEvent::DELETE, HistoryEvent::DELETE_T, HistoryEvent::DELETE_T_ALL])
                    && !in_array(SoftDeletes::class, class_uses($this->params['modelClass']), true)) {
                    return $value;
                }

                return $this->makeLink($this->params['modelShowUrl'])($value, $row, $rowOriginal);
            },
            'data' => function ($value, $row, $rowOriginal) {
                $data = json_decode($value, true);
                $old = $data['old'];
                $new = $data['new'];
                $html = '';
                foreach (['__common', '__t'] as $specKey) {
                    $oldSpec = $old[$specKey] ?? [];
                    $newSpec = $new[$specKey] ?? [];
                    if (empty($oldSpec) && empty($newSpec)) {
                        continue;
                    }

                    if ($specKey === '__t') {
                        if (array_key_exists('__lang', $oldSpec)) {
                            $lang = $oldSpec['__lang'];
                            unset($oldSpec['__lang']);
                        }
                        if (array_key_exists('__lang', $newSpec)) {
                            $lang = $newSpec['__lang'];
                            unset($newSpec['__lang']);
                        }
                        if (array_key_exists('__id', $oldSpec)) {
                            $id = $oldSpec['__id'];
                            unset($oldSpec['__id']);
                        }
                        if (array_key_exists('__id', $newSpec)) {
                            $id = $newSpec['__id'];
                            unset($newSpec['__id']);
                        }
                    } else {
                        $lang = null;
                        $id = null;
                    }

                    $keys = array_unique(array_merge(array_keys($oldSpec), array_keys($newSpec)));
                    $historyCasts = $this->params['historyClass']::historyCasts();

                    if (in_array($row->history_event_id, [HistoryEvent::CREATE_T, HistoryEvent::UPDATE_T])) {
                        $html .= "<div>[ model_id ]: <strong>{$id}</strong></div>";
                    }
                    foreach ($keys as $key) {
                        $oldValue = $oldSpec[$key] ?? null;
                        $newValue = $newSpec[$key] ?? null;
                        if (array_key_exists($key, $historyCasts)) {
                            $castData = $historyCasts[$key];
                            if (array_key_exists('callback', $castData)) {
                                $oldValue = $oldValue !== null ? $castData['callback']($oldValue) : null;
                                $newValue = $newValue !== null ? $castData['callback']($newValue) : null;
                            } else {
                                $field = $castData['field'];
                                $oldValue = $oldValue !== null ? $castData['model']::find($oldValue)->$field : null;
                                $newValue = $newValue !== null ? $castData['model']::find($newValue)->$field : null;
                            }
                        }
                        $oldValue = $oldValue === null ? '<span class="badge text-bg-secondary">NULL</span>' : $oldValue;
                        $newValue = $newValue === null ? '<span class="badge text-bg-secondary">NULL</span>' : $newValue;

                        if (is_bool($oldValue)) {
                            $oldValue = boolIcon($oldValue);
                        }
                        if (is_bool($newValue)) {
                            $newValue = boolIcon($newValue);
                        }

                        $attribute = __("validation.attributes.{$key}");
                        $caption = $lang === null ? $attribute : "(<strong>{$lang}</strong>) {$attribute}";
                        $html .= "<div>[{$caption}]: {$oldValue} => <strong>{$newValue}</strong></div>";
                    }
                }

                return $html;
            },
        ];
    }
}

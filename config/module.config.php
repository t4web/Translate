<?php

return [

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'router' => [
        'routes' => [
            'admin-translate-words' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/admin/translate/words',
                    'defaults' => [
                        '__NAMESPACE__' => 'T4webTranslate\Controller\Admin',
                        'controller' => 'Lists',
                        'action' => 'words-list',
                    ]
                ]
            ],
            'admin-translate-word' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/admin/translate/word[/:id]',
                    'defaults' => [
                        '__NAMESPACE__' => 'T4webTranslate\Controller\Admin',
                        'controller' => 'Word',
                        'action' => 'edit',
                    ],
                    'constraints' => [
                        'id' => '[1-9][0-9]*',
                    ],
                ]
            ],
            'admin-translate-word-save' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/admin/translate/word/save',
                    'defaults' => [
                        '__NAMESPACE__' => 'T4webTranslate\Controller\Admin',
                        'controller' => 'Word',
                        'action' => 'save',
                    ],
                ]
            ],
            'admin-translate-word-delete' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/admin/translate/word/delete[/:id]',
                    'defaults' => [
                        '__NAMESPACE__' => 'T4webTranslate\Controller\Admin',
                        'controller' => 'Word',
                        'action' => 'delete',
                    ],
                    'constraints' => [
                        'id' => '[1-9][0-9]*',
                    ],
                ]
            ],
            'admin-translate-ajax-word-save' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/admin/translate/ajax/word/save[/:id]',
                    'defaults' => [
                        '__NAMESPACE__' => 'T4webTranslate\Controller\Admin',
                        'controller' => 'AjaxWord',
                        'action' => 'save',
                    ],
                    'constraints' => [
                        'id' => '[1-9][0-9]*',
                    ],
                ]
            ],
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'translate-init' => [
                    'options' => [
                        'route' => 'translate init',
                        'defaults' => [
                            '__NAMESPACE__' => 'T4webTranslate\Controller\Console',
                            'controller' => 'Init',
                            'action' => 'run'
                        ]
                    ]
                ],
            ]
        ]
    ],
    'controller_action_injections' => [
        'T4webTranslate\Controller\Admin\ListsController' => [
            'wordsListAction' => [
                'Request',
                'T4webTranslate\Words\Service\Finder',
                'T4webTranslate\Words\Form\FilterForm',
                'T4webTranslate\Controller\ViewModel\Admin\ListViewModel'
            ],
        ],
        'T4webTranslate\Controller\Admin\WordController' => [
            'editAction' => [
                function ($serviceLocator) {
                    return $serviceLocator->get('ControllerPluginManager')->get('params');
                },
                'T4webTranslate\Words\Service\Finder',
                'T4webTranslate\Words\Form\EntityForm',
                'T4webTranslate\Controller\ViewModel\Admin\EntityViewModel'
            ],
            'saveAction' => [
                'Request',
                'T4webTranslate\Words\Service\Create',
                'T4webTranslate\Words\Form\EntityForm',
                'T4webTranslate\Controller\ViewModel\Admin\EntityViewModel',
                function ($serviceLocator) {
                    return $serviceLocator->get('ControllerPluginManager')->get('redirect');
                }
            ],
            'deleteAction' => [
                'Request',
                function ($serviceLocator) {
                    return $serviceLocator->get('ControllerPluginManager')->get('params');
                },
                'T4webTranslate\Words\Service\Delete',
                function ($serviceLocator) {
                    return $serviceLocator->get('ControllerPluginManager')->get('redirect');
                }
            ],
        ],
        'T4webTranslate\Controller\Admin\AjaxWordController' => [
            'saveAction' => [
                function ($serviceLocator) {
                    return $serviceLocator->get('ControllerPluginManager')->get('params');
                },
                'Request',
                'Response',
                'T4webTranslate\Words\Form\EntityForm',
                'T4webTranslate\Words\Service\Finder',
                'T4webTranslate\Words\Service\Update',
                'T4webTranslate\Words\Form\EntityForm',
                'T4webTranslate\Controller\ViewModel\Admin\AjaxViewModel'
            ],
        ],
    ],
    'db' => [
        'tables' => [
            'translate-words' => [
                'name' => 'words',
                'columnsAsAttributesMap' => [
                    'id' => 'id',
                    'lang_id' => 'langId',
                    'key' => 'key',
                    'translate' => 'translate',
                ],
            ],
            'translate-languages' => [
                'name' => 'languages',
                'columnsAsAttributesMap' => [
                    'id' => 'id',
                    'code' => 'code',
                    'name' => 'name',
                    'locale' => 'locale',
                    'default' => 'default',
                ],
            ],
            'translate-category' => [
                'name' => 'categories_tr',
                'columnsAsAttributesMap' => [
                    'id' => 'id',
                    'entity_id' => 'entityId',
                    'lang_id' => 'langId',
                    'name' => 'name',
                ],
            ],
            'translate-single' => [
                'name' => 'params_tr',
                'columnsAsAttributesMap' => [
                    'id' => 'id',
                    'entity_id' => 'entityId',
                    'lang_id' => 'langId',
                    'name' => 'name',
                ],
            ],
            'translate-value' => [
                'name' => 'params_values_tr',
                'columnsAsAttributesMap' => [
                    'id' => 'id',
                    'entity_id' => 'entityId',
                    'lang_id' => 'langId',
                    'value' => 'value',
                ],
            ],
        ],
        'dependencies' => [
            'Words' => [
                'Translate' => [
                    'Languages' => [
                        'table' => 'languages',
                        'rule' => 'languages.id = words.lang_id',
                    ],
                ],
            ],
            'Translate' => [
                'Translate' => [
                    'Languages' => [
                        'table' => 'languages',
                        'rule' => 'languages.id = lang_id',
                    ],
                ],
            ],
        ],
    ],
    'criteries' => [
        'Words' => [
            'empty' => ['table' => 'words'],
            'Id' => [
                'table' => 'words',
                'field' => 'id',
                'buildMethod' => 'addFilterEqual'
            ],
            'langId' => [
                'table' => 'words',
                'field' => 'lang_id',
                'buildMethod' => 'addFilterEqual'
            ],
            'key' => [
                'table' => 'words',
                'field' => 'key',
                'buildMethod' => 'addFilterLike'
            ],
            'Order' => [
                'table' => 'words',
                'buildMethod' => 'order'
            ],
            'Limit' => [
                'table' => 'words',
                'buildMethod' => 'limit',
            ],
            'Page' => [
                'table' => 'words',
                'buildMethod' => 'page',
            ],
        ],
        'Languages' => [
            'empty' => ['table' => 'languages'],
            'Id' => [
                'table' => 'languages',
                'field' => 'id',
                'buildMethod' => 'addFilterEqual'
            ],
            'code' => [
                'table' => 'languages',
                'field' => 'code',
                'buildMethod' => 'addFilterEqual'
            ],
            'Locale' => [
                'table' => 'languages',
                'field' => 'locale',
                'buildMethod' => 'addFilterEqual'
            ],
        ],
        'Translate' => [
            'empty' => [],
            'Id' => [
                'field' => 'id',
                'buildMethod' => 'addFilterEqual'
            ],
            'EntityId' => [
                'field' => 'entity_id',
                'buildMethod' => 'addFilterEqual'
            ],
            'EntityIds' => [
                'field' => 'entity_id',
                'buildMethod' => 'addFilterIn'
            ],
            'LangId' => [
                'field' => 'lang_id',
                'buildMethod' => 'addFilterEqual'
            ],
        ],
    ],
    'content' => [
        'categories' => [
            'category' => [
                'entity' => 'Categories\Category\Category',
                'fields' => ['name'],
                'events' => [
                    'select:post' => 'Categories\Category\Category',
                    'create:post' => 'Categories\Category\Service\Create',
                    'update:pre' => 'Categories\Category\Service\Update',
                ],
            ]
        ],
        'params' => [
            'single' => [
                'entity' => 'Params\Parameter\Single',
                'events' => [
                    'select:post' => 'Params\Parameter\Single',
                    'create:post' => 'Params\Parameter\Service\Create',
                    'update:pre' => 'Params\Parameter\Service\Update',
                ]
            ],
            'value' => [
                'entity' => 'Params\Parameter\Single',
                'events' => [
                    'select:post' => 'Params\Value\Value',
                    'params-values-create:post' => 'Params\Value\Service\Creator',
                    'update:pre' => 'Params\Value\Service\Update',
                ]
            ]
        ]
    ],
    'translator' => [
        'remote_translation' => [
            [
                'text_domain' => 'default',
                'type' => 'T4webTranslate\I18n\Translator\Loader\DbLoader',
            ],
        ],
    ],
];
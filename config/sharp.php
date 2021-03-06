<?php

use App\Sharp\ProfilSharp;
use Illuminate\Support\Facades\Route;

return [

    // Required. The name of your app, as it will be displayed in Sharp.
    "name" => "Administration",

    // Optional. You can here customize the URL segment in which Sharp will live. Default in "sharp".
    "custom_url_segment" => "adminsharp",

    // Optional. You can prevent Sharp version to be displayed in the page title. Default is true.
    "display_sharp_version_in_title" => false,

    // Optional. Handle extensions.
    //    "extensions" => [
    //        "assets" => [
    //            "strategy" => "asset",
    //            "head" => [
    //                "/css/inject.css",
    //            ],
    //        ],
    //
    //        "activate_custom_fields" => false,
    //    ],

    // Required. Your entities list; each one must define a "list",
    // and can define "form", "validator", "policy" and "authorizations".
    "entities" => [
        "profil" => [
            "show" => \App\Sharp\ProfilSharpShow::class,
            "form" => \App\Sharp\ProfilSharpForm::class,
        ],
        "user" => [
            "list" => \App\Sharp\UserSharpList::class,
            "form" => \App\Sharp\UserSharpForm::class,
            //    "validator" => \App\Sharp\MyEntitySharpValidator::class,
            "policy" => \App\Sharp\Policies\UserPolicy::class,
        ],
        "match-foot" => [
            "list" => \App\Sharp\MatchFootSharpList::class,
            "form" => \App\Sharp\MatchFootSharpForm::class,
            // "validator" => \App\Sharp\MatchSharpValidator::class,
            "policy" => \App\Sharp\Policies\MatchFootPolicy::class,
        ],
        "match-hand" => [
            "list" => \App\Sharp\MatchHandSharpList::class,
            "form" => \App\Sharp\MatchHandSharpForm::class,
            // "validator" => \App\Sharp\MatchSharpValidator::class,
            "policy" => \App\Sharp\Policies\MatchHandPolicy::class,
        ],
        "article" => [
            "list" => \App\Sharp\ArticleSharpList::class,
            "form" => \App\Sharp\ArticleSharpForm::class,
            //    "validator" => \App\Sharp\MyEntitySharpValidator::class,
            "policy" => \App\Sharp\Policies\ArticlePolicy::class,
        ],
        "journee" => [
            "list" => \App\Sharp\JourneeSharpList::class,
            "form" => \App\Sharp\JourneeSharpForm::class,
            //    "validator" => \App\Sharp\MyEntitySharpValidator::class,
            "policy" => \App\Sharp\Policies\JourneePolicy::class,
        ],
    ],

    // Optional. Your dashboards list; each one must define a "view", and can define "policy".
    // "dashboards" => [
    //    "dash" => [
    // "view" => \App\Sharp\DashAdmin::class,
    // "policy" => \App\Sharp\Policies\DashAdminPolicy::class,
    //    ],
    // ],

    // Optional. Your global filters list, which will be displayed in the main menu.
    "global_filters" => [
        //        "my_global_filter" => \App\Sharp\Filters\MyGlobalFilter::class
    ],

    // Required. The main menu (left bar), which may contain links to entities, dashboards
    // or external URLs, grouped in categories.
    "menu" => [
        [
            "label" => "Profil",
            "icon" => "fa-user",
            "entity" => "profil",
            "single" => true
        ],
        [
            "label" => "Membres",
            "icon" => "fa-user",
            "entity" => "user",
        ],
        [
            "label" => "Matches",
            "entities" => [
                [
                    "label" => "Football",
                    "icon" => "fa-list",
                    "entity" => "match-foot",
                ],
                [
                    "label" => "Handball",
                    "icon" => "fa-list",
                    "entity" => "match-hand",
                ],
            ]
        ],
        [
            "label" => "Articles",
            "entities" => [
                [
                    "label" => "Liste",
                    "icon" => "fa-list-ul",
                    "entity" => "article"
                ],
                [
                    "label" => "Modifier",
                    "icon" => "el-icon-refresh",
                    "url" => "/admin/article/select"
                ],
                [
                    "label" => "Nouveau",
                    "icon" => "fa-plus-circle",
                    "url" => "/admin/article/create"
                ]
            ]
        ],
        [
            "label" => "Journ??es",
            "entities" => [
                [
                    "label" => "Journ??es",
                    "icon" => "fa-calendar",
                    "entity" => "journee",
                ],
                [
                    "label" => "Par saison",
                    "icon" => "fa-calendar",
                    "url" => "/admin/autres/journees/multi/select",
                ],
            ]
        ],
        [
            "label" => "Accueil",
            "icon" => "fa-home",
            "url" => '/'
        ],
    ],

    // Optional. Your file upload configuration.
    "uploads" => [
        // Tmp directory used for file upload.
        "tmp_dir" => env("SHARP_UPLOADS_TMP_DIR", "tmp"),

        // These two configs are used for thumbnail generation inside Sharp.
        "thumbnails_disk" => env("SHARP_UPLOADS_THUMBS_DISK", "public"),
        "thumbnails_dir" => env("SHARP_UPLOADS_THUMBS_DIR", "thumbnails"),
    ],

    // Optional. Auth related configuration.
    "auth" => [
        // Name of the login and password attributes of the User Model.
        "login_attribute" => "email",
        "password_attribute" => "password",

        // Name of the attribute used to display the current user in the UI.
        "display_attribute" => "name",

        // Optional additional auth check.
        "check_handler" => \App\Sharp\Auth\SharpCheckHandler::class,

        // Optional custom guard
        //    "guard" => "web",
    ],

    //    "login_page_message_blade_path" => env("SHARP_LOGIN_PAGE_MESSAGE_BLADE_PATH", "sharp/_login-page-message"),

];

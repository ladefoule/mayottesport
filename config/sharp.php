<?php
use Illuminate\Support\Facades\Route;

return [

    // Required. The name of your app, as it will be displayed in Sharp.
    "name" => "Administration",

    // Optional. You can here customize the URL segment in which Sharp will live. Default in "sharp".
    "custom_url_segment" => "admin",

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
        //    "validator" => \App\Sharp\MyEntitySharpValidator::class,
        //    "policy" => \App\Sharp\Policies\MyEntityPolicy::class,
       ],
    ],

    // Optional. Your dashboards list; each one must define a "view", and can define "policy".
    "dashboards" => [
    //    "profil" => [
        //    "view" => \App\Sharp\Profil::class,
        //    "policy" => \App\Sharp\Policies\MyDashboardPolicy::class,
    //    ],
    ],

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
            "label" => "CRUD",
            "icon" => "fa-database",
            // "dashboard" => "CRUD",
            "url" => '/admin/crud'
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
    //    "check_handler" => \App\Sharp\Auth\SharpCheckHandler::class,

        // Optional custom guard
    //    "guard" => "sharp",
    ],

//    "login_page_message_blade_path" => env("SHARP_LOGIN_PAGE_MESSAGE_BLADE_PATH", "sharp/_login-page-message"),

];

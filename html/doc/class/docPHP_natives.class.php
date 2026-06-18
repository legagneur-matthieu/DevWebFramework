<?php

class docPHP_natives {

    public static function get_methods() {
        return get_class_methods(__CLASS__);
    }

    public function __construct() {
        ?>
        <p>
            Voici quelques classes natives de DWF et quelques exemples d'utilisation, pour plus d'informations, chaque classe et fonction sont commentées (document technique) <br />
            si une classe/fonction a mal été commentée ( ou pas du tout commentée), merci de nous le signaler. <br />
            (il s'agit de quelques-unes des classes les plus utiles du framework, le framework compte plus de <?= count(glob("../../dwf/class/*.class.php")); ?> classes natives)
        </p>
        <?php
        $functions = get_class_methods(__CLASS__);
        natcasesort($functions);
        $ul = [];
        foreach ($functions as $n) {
            if (!in_array($n, ["get_methods", "__construct"])) {
                $ul[] = html_structures::a_link("index.php?page=web&doc=classes_natives&native=$n", strtr(ucfirst($n), array("_" => " ")));
            }
        }
        echo html_structures::ul($ul);
    }

    public static function admin_controle() {
        docPHP_natives_ac::admin_controle();
    }

    public static function application() {
        docPHP_natives_ac::application();
    }

    public static function audio() {
        docPHP_natives_ac::audio();
    }

    public static function auth() {
        docPHP_natives_ac::auth();
    }

    /* public static function ban_ip() {

      }

      public static function bbParser() {

      }

      public static function bdd() {

      } */

    public static function bootstrap_theme() {
        docPHP_natives_ac::bootstrap_theme();
    }

    public static function bunnyfonts() {
        docPHP_natives_ac::bunnyfonts();
    }

    public static function cache() {
        docPHP_natives_ac::cache();
    }

    public static function captcha() {
        docPHP_natives_ac::captcha();
    }

    public static function cards() {
        docPHP_natives_ac::cards();
    }

    public static function change_reload() {
        docPHP_natives_ac::change_reload();
    }

    public static function check_PHPDoc() {
        docPHP_natives_ac::check_PHPDoc();
    }

    public static function check_password() {
        docPHP_natives_ac::check_password();
    }

    public static function citations() {
        docPHP_natives_ac::citations();
    }

    public static function ckeditor() {
        docPHP_natives_ac::ckeditor();
    }

    public static function cli() {
        docPHP_natives_ac::cli();
    }

    public static function compact_css() {
        docPHP_natives_ac::compact_css();
    }

    public static function cookieaccept() {
        docPHP_natives_ac::cookieaccept();
    }

    public static function css() {
        docPHP_natives_ac::css();
    }

    public static function cytoscape() {
        docPHP_natives_ac::cytoscape();
    }

    public static function datatable() {
        docPHP_natives_df::datatable();
    }

    public static function ddg__ddg_api() {
        docPHP_natives_df::ddg__ddg_api();
    }

    public static function debug() {
        docPHP_natives_df::debug();
    }

    public static function dlc() {
        docPHP_natives_df::dlc();
    }

    public static function downloader() {
        docPHP_natives_df::downloader();
    }

    public static function dwf_exception() {
        docPHP_natives_df::dwf_exception();
    }

    public static function easteregg() {
        docPHP_natives_df::easteregg();
    }

    public static function entity_generator() {
        docPHP_natives_df::entity_generator();
    }

    public static function entity_model() {
        docPHP_natives_df::entity_model();
    }

    public static function espeak() {
        docPHP_natives_df::espeak();
    }

    public static function event() {
        docPHP_natives_df::event();
    }

    public static function export_dwf() {
        docPHP_natives_df::export_dwf();
    }

    public static function fancybox() {
        docPHP_natives_df::fancybox();
    }

    public static function fb() {
        docPHP_natives_df::fb();
    }

    public static function file_explorer() {
        docPHP_natives_df::file_explorer();
    }

    public static function fluent() {
        docPHP_natives_df::fluent();
    }

    public static function form() {
        docPHP_natives_df::form();
    }

    public static function freetile() {
        docPHP_natives_df::freetile();
    }

    public static function ftp_explorer() {
        docPHP_natives_df::ftp_explorer();
    }

    public static function fullcalendar() {
        docPHP_natives_df::fullcalendar();
    }

    public static function g_agenda() {
        docPHP_natives_gl::g_agenda();
    }

    public static function g_elFinder() {
        docPHP_natives_gl::g_elFinder();
    }

    public static function gestion_article() {
        docPHP_natives_gl::gestion_article();
    }

    public static function giphy() {
        docPHP_natives_gl::giphy();
    }

    public static function git() {
        docPHP_natives_gl::git();
    }

    public static function google_oauth() {
        docPHP_natives_gl::google_oauth();
    }

    public static function graphique() {
        docPHP_natives_gl::graphique();
    }

    public static function html5() {
        docPHP_natives_gl::html5();
    }

    public static function html_structures() {
        docPHP_natives_gl::html_structures();
    }

    public static function http2() {
        docPHP_natives_gl::http2();
    }

    public static function ip_access() {
        docPHP_natives_gl::ip_access();
    }

    public static function ip_api() {
        docPHP_natives_gl::ip_api();
    }

    public static function js() {
        docPHP_natives_gl::js();
    }

    public static function leaflet() {
        docPHP_natives_gl::leaflet();
    }

    public static function log_file() {
        docPHP_natives_gl::log_file();
    }

    public static function log_mail() {
        docPHP_natives_gl::log_mail();
    }

    public static function lorem_ipsum() {
        docPHP_natives_gl::lorem_ipsum();
    }

    public static function lurl() {
        docPHP_natives_gl::lurl();
    }

    public static function mail() {
        docPHP_natives_mr::mail();
    }

    public static function maskNumber() {
        docPHP_natives_mr::maskNumber();
    }

    public static function math() {
        docPHP_natives_mr::math();
    }

    public static function messageries() {
        docPHP_natives_mr::messageries();
    }

    public static function mocodo() {
        docPHP_natives_mr::mocodo();
    }

    public static function modal() {
        docPHP_natives_mr::modal();
    }

    public static function monaco_editor() {
        docPHP_natives_mr::monaco_editor();
    }

    public static function monaco_highlighter() {
        docPHP_natives_ms::monaco_highlighter();
    }

    public static function MSEdgeTTS() {
        docPHP_natives_ms::MSEdgeTTS();
    }

    public static function MSEdgeTTS_JS() {
        docPHP_natives_ms::MSEdgeTTS_JS();
    }

    public static function openweather() {
        docPHP_natives_mr::openweather();
    }

    public static function pagination() {
        docPHP_natives_mr::pagination();
    }

    public static function paypal() {
        docPHP_natives_mr::paypal();
    }

    public static function php_finediff() {
        docPHP_natives_mr::php_finediff();
    }

    public static function php_header() {
        docPHP_natives_mr::php_header();
    }

    public static function php_simple_formatter() {
        docPHP_natives_mr::php_simple_formatter();
    }

    public static function phpini() {
        docPHP_natives_mr::phpini();
    }

    public static function pollinanitons() {
        docPHP_natives_mr::pollinanitons();
    }

    public static function printer() {
        docPHP_natives_mr::printer();
    }

    public static function pseudo_cron() {
        docPHP_natives_mr::pseudo_cron();
    }

    public static function ratioblocks() {
        docPHP_natives_mr::ratioblocks();
    }

    public static function reveal() {
        docPHP_natives_mr::reveal();
    }

    public static function reversoLib() {
        docPHP_natives_mr::reversoLib();
    }

    public static function robotstxt() {
        docPHP_natives_mr::robotstxt();
    }

    public static function schoolbreak() {
        docPHP_natives_ms::schoolbreak();
    }

    public static function scraperAPI() {
        docPHP_natives_ms::scraperAPI();
    }

    public static function security__csp() {
        docPHP_natives_ms::security__csp();
    }

    public static function selectorDOM() {
        docPHP_natives_ms::selectorDOM();
    }

    public static function service() {
        docPHP_natives_ms::service();
    }

    public static function session() {
        docPHP_natives_ms::session();
    }

    public static function shuffle_letters() {
        docPHP_natives_ms::shuffle_letters();
    }

    public static function singleton() {
        docPHP_natives_ms::singleton();
    }

    public static function sitemap() {
        docPHP_natives_ms::sitemap();
    }

    public static function sms_gateway() {
        docPHP_natives_ms::sms_gateway();
    }

    public static function sql_backup() {
        docPHP_natives_ms::sql_backup();
    }

    public static function sse_sender() {
        docPHP_natives_ms::sse_sender();
    }

    public static function stalactite() {
        docPHP_natives_ms::stalactite();
    }

    public static function statistiques() {
        docPHP_natives_ms::statistiques();
    }

    public static function stripe() {
        docPHP_natives_ms::stripe();
    }

    public static function sub_menu() {
        docPHP_natives_ms::sub_menu();
    }

    public static function tags() {
        docPHP_natives_tz::tags();
    }

    public static function task_manager() {
        docPHP_natives_tz::task_manager();
    }

    public static function template() {
        docPHP_natives_tz::template();
    }

    public static function tenor() {
        docPHP_natives_tz::tenor();
    }

    public static function thread_manager() {
        docPHP_natives_tz::thread_manager();
    }

    public static function time() {
        docPHP_natives_tz::time();
    }

    public static function tinymce() {
        docPHP_natives_tz::tinymce();
    }

    public static function tor() {
        docPHP_natives_tz::tor();
    }

    public static function trad() {
        docPHP_natives_tz::trad();
    }

    public static function update_dwf() {
        docPHP_natives_tz::update_dwf();
    }

    public static function video() {
        docPHP_natives_tz::video();
    }

    public static function vpage() {
        docPHP_natives_tz::vpage();
    }

    public static function vticker() {
        docPHP_natives_tz::vticker();
    }

    public static function w3c_validate() {
        docPHP_natives_tz::w3c_validate();
    }

    public static function websocket() {
        docPHP_natives_tz::websocket();
    }

    public static function wled() {
        docPHP_natives_tz::wled();
    }

    public static function writer() {
        docPHP_natives_tz::writer();
    }
}

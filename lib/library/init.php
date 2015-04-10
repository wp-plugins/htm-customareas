<?php
class HTMCustomAreasInit{
    function __construct($dir) {


        include_once $dir.'/lib/library/widgetcontroller.php';
        include_once $dir.'/lib/library/option.php';
        include_once $dir.'/lib/library/corecontroller.php';
        include_once $dir.'/lib/library/ajaxcontroller.php';
        include_once $dir.'/lib/library/formhelper.php';
        include_once $dir.'/lib/admin/controller/admin.php';
        include_once $dir.'/lib/admin/controller/customareas.php';
        include_once $dir.'/lib/admin/controller/customareaselector.php';

        //Load admin
        $customareaadmin = new HTMCustomAreasAdmin($dir);
        $customarea = new HTMCustomArea($dir);
        $selector = new HTMCustomAreasSelector($dir);

        if(is_admin() === false) { 
            include_once $dir.'/lib/frontend/controller/shortcodes.php';
            $shortcodes = new HTMCustomAreasShortcodes($dir);
        }

    }
}
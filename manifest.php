<?php return array(
    'manifest-version' => '1.7.4',
    'manifest-attributes' =>
        array(
            'license' => 'This is a License for Unisender components.
This component is destributed as open-source package.',
            'readme' => '--------------------
Extra: Unisender Integration
--------------------
Version: 1.7.1
 
Unisender module for MODx Revolution. Visit www.unisender.com for details.',
            'changelog' => 'Changelog file for Unisender component.
 
Unisender 1.0
====================================
- No changelog now :)

Unisender 1.7.1
====================================

1. Fix bugs in subscribers list.
2. Fix bugs with subscription for users.
3. Fix problems during install.
4. Fix bug with "double_optin".


Unisender 1.7.2
====================================

1. Fix bug on E-mail sending
2. Fix several bugs in code


Unisender 1.7.3
====================================

1. Added internal statistic


Unisender 1.7.4
====================================

1. Fix bug with wrong subscribe urls

',
            'setup-options' => 'unisender-1.7.4/setup-options.php'
        ),
    'manifest-vehicles' =>
        array(
            0 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modNamespace',
                    'guid' => '57b6ccdc9f5166dc87963ab3647d68da',
                    'native_key' => 'unisender',
                    'filename' => 'modNamespace/1128c6ad442e6511ae2fcca441f89b86.vehicle',
                    'namespace' => 'unisender',
                ),
            1 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modPlugin',
                    'guid' => '652d37fc32f47938f8806c27982711b6',
                    'native_key' => 1,
                    'filename' => 'modPlugin/9c1e603c5f847218572bb750e738e930.vehicle',
                    'namespace' => 'unisender',
                ),
            2 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modCategory',
                    'guid' => 'bbf958d80565329118ffc56f48695c63',
                    'native_key' => 1,
                    'filename' => 'modCategory/7c172227fc8994cf7181fd87a7d59a11.vehicle',//TODO
                    'namespace' => 'unisender',
                ),
            3 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modSystemSetting',
                    'guid' => 'a1543d54c8b17dec86dd46ecea66db8b',
                    'native_key' => 'unisender.api_path',
                    'filename' => 'modSystemSetting/f7e7fc4f9beaae5ae4da01f183c65350.vehicle',
                    'namespace' => 'unisender',
                ),
            4 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modSystemSetting',
                    'guid' => 'ee611882bd749f93124c328ab49246a8',
                    'native_key' => 'unisender.assets_url',
                    'filename' => 'modSystemSetting/d41a535b38903d26341301350839c5ce.vehicle',
                    'namespace' => 'unisender',
                ),
            5 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modSystemSetting',
                    'guid' => '4e61a9a34a2848f7284a37596fc6acef',
                    'native_key' => 'unisender.core_path',
                    'filename' => 'modSystemSetting/b48e70a5c16c95c0e302a61db447f7d5.vehicle',
                    'namespace' => 'unisender',
                ),
            6 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modSystemSetting',
                    'guid' => '7914fcc70f3d044d3a75e5b8c275d485',
                    'native_key' => 'unisender_api',
                    'filename' => 'modSystemSetting/f4341798ee9a3e785e544bfee70519ba.vehicle',
                    'namespace' => 'unisender',
                ),
            7 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modSystemSetting',
                    'guid' => '164aec5b9c827794a8090754f244a52f',
                    'native_key' => 'unisender_message_title_field',
                    'filename' => 'modSystemSetting/bb10b3688ef7861e5798b5c22813fab8.vehicle',
                    'namespace' => 'unisender',
                ),
            8 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modSystemSetting',
                    'guid' => '3410a219b4f326498d48530bfd27f467',
                    'native_key' => 'unisender_sender_email',
                    'filename' => 'modSystemSetting/5199aabe5fe22e2e87f7eabf54c5ee27.vehicle',
                    'namespace' => 'unisender',
                ),
            9 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modSystemSetting',
                    'guid' => '30cde90ff2c848afb41971179fae531f',
                    'native_key' => 'unisender_sender_name',
                    'filename' => 'modSystemSetting/f6dd6e3192e98765cf9bf7076e481b8c.vehicle',
                    'namespace' => 'unisender',
                ),
            10 =>
                array(
                    'vehicle_package' => 'transport',
                    'vehicle_class' => 'xPDOObjectVehicle',
                    'class' => 'modMenu',
                    'guid' => 'a7cad92b0d344158e3b18b65b63cf949',
                    'native_key' => 1,
                    'filename' => 'modMenu/85de9fccf7a2dbe7f157f28c19cb2978.vehicle', //TODO
                    'namespace' => 'unisender',
                ),
        ),
);
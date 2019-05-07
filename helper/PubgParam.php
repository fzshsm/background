<?php
namespace app\helper;


class PubgParam{

    public function getParam(){
        return [
            'Region' => 'AS Server',
            'Title' => '$StarRankTest',
            'Mode' => 'BATTLEROYALE_CUSTOM',
            'MaxPlayers' => 100,
            'WarmupTime' => 60,
            'Dbno' => true,
            'ReviveCastingTime' => 10,
            'MultiplierGroggyDamagePerSecond' => 1,
            'FPSOnly' => true,
           // 'ZombieCameraView' => 'TpsOnly',
            'Lock' => true,
            'MapId' => '/Game/Maps/Erangel/Erangel_Main',
            'MapOpt' => 'Clear',
            'SpecSolo' => false,
            'TeamSize' => 4,
            'TeamKillDamageModifier' => 1,
            //'IsZombie' => false,
            'MultiplierZombieToZombieDamage' => 0,
            'CarePackageFreq' => 0,
            'MultiplierPunchDamage' => 1,
            //'StreamerEventMode' => false,
           // 'IsESportsMode' => false,
            'Item' => 1,
            'Equip' => 1,
            //'IsLeague' => false,
            'UserSlots' => [],
            'WeatherChange_StartTimeMin' => 0,
            'WeatherChange_StartTimeMax' => 300,
            'WeatherChange_DurationMin' => 0,
            'WeatherChange_DurationMax' => 300,
            'WeatherChange_WeightNoChange' => 1,
            'WeatherChange_WeightChangeToRain' => 1,
            'WeatherChange_WeightChangeToFog' => 1,
            'IsMultiTeams' => false,
            'GoalScore' => 150,
            'TimeLimit' => 900,
            'TeamElimination' => false,
            'GroggyDamagePerSecond' => 5,
            'HealthByRevive' => 50,
            'BlueZoneStatic' => false,
            'BlueZoneSize' => 0.05,
            'RespawnType' => 'AIR',
            'RespawnPeriod' => 40,
            'RespawnEquipment' => 'SMG',
            'CarePackageType' => 'Basic',
            'CarePackagePeriod' => 90,
            'RespawnOffTimeLeftRatio' => 0.2,
            'PublicSpectating' => true,
            'KillerSpectating' => true,
            'BlueZoneCentralizationFactor' => 0,
            'PlayzoneProgress' => 1,
            'EndCircleLocationRate' => 0,
            'EndCircleLocationArea1' => 1,
            'EndCircleLocationArea2' => 1,
            'EndCircleLocationArea3' => 1,
            'Phase1_StartDelay' => 120,
            'Phase1_WarningDuration' => 300,
            'Phase1_ReleaseDuration' => 300,
            'Phase1_GasDamagePerSecond' => 0.4,
            'Phase1_RadiusRate' => 0.4,
            'Phase1_SpreadRatio' => 0.5,
            'Phase1_CircleGenerationAlgorithm' => 0,
            'Phase1_LandRatio' => 0.7,
            'Phase2_StartDelay' => 0,
            'Phase2_WarningDuration' => 200,
            'Phase2_ReleaseDuration' => 140,
            'Phase2_GasDamagePerSecond' => 0.6,
            'Phase2_RadiusRate' => 0.65,
            'Phase2_SpreadRatio' => 0.5,
            'Phase2_CircleGenerationAlgorithm' => 0,
            'Phase2_LandRatio' => 0.7,
            'Phase3_StartDelay' => 0,
            'Phase3_WarningDuration' => 150,
            'Phase3_ReleaseDuration' => 90,
            'Phase3_GasDamagePerSecond' => 0.8,
            'Phase3_RadiusRate' => 0.5,
            'Phase3_SpreadRatio' => 0.5,
            'Phase3_CircleGenerationAlgorithm' => 0,
            'Phase3_LandRatio' => 0.7,
            'Phase4_StartDelay' => 0,
            'Phase4_WarningDuration' => 120,
            'Phase4_ReleaseDuration' => 60,
            'Phase4_GasDamagePerSecond' => 1,
            'Phase4_RadiusRate' => 0.5,
            'Phase4_SpreadRatio' => 0.5,
            'Phase4_CircleGenerationAlgorithm' => 0,
            'Phase4_LandRatio' => 0.7,
            'Phase5_StartDelay' => 0,
            'Phase5_WarningDuration' => 120,
            'Phase5_ReleaseDuration' => 40,
            'Phase5_GasDamagePerSecond' => 3,
            'Phase5_RadiusRate' => 0.5,
            'Phase5_SpreadRatio' => 0.5,
            'Phase5_CircleGenerationAlgorithm' => 0,
            'Phase5_LandRatio' => 0.7,
            'Phase6_StartDelay' => 0,
            'Phase6_WarningDuration' => 90,
            'Phase6_ReleaseDuration' => 30,
            'Phase6_GasDamagePerSecond' => 5,
            'Phase6_RadiusRate' => 0.5,
            'Phase6_SpreadRatio' => 0.5,
            'Phase6_CircleGenerationAlgorithm' => 0,
            'Phase6_LandRatio' => 0.7,
            'Phase7_StartDelay' => 0,
            'Phase7_WarningDuration' => 90,
            'Phase7_ReleaseDuration' => 30,
            'Phase7_GasDamagePerSecond' => 7,
            'Phase7_RadiusRate' => 0.5,
            'Phase7_SpreadRatio' => 0.5,
            'Phase7_CircleGenerationAlgorithm' => 0,
            'Phase7_LandRatio' => 0.7,
            'Phase8_StartDelay' => 0,
            'Phase8_WarningDuration' => 60,
            'Phase8_ReleaseDuration' => 30,
            'Phase8_GasDamagePerSecond' => 9,
            'Phase8_RadiusRate' => 0.5,
            'Phase8_SpreadRatio' => 0.5,
            'Phase8_CircleGenerationAlgorithm' => 0,
            'Phase8_LandRatio' => 0.7,
            'Phase9_StartDelay' => 180,
            'Phase9_WarningDuration' => 15,
            'Phase9_ReleaseDuration' => 15,
            'Phase9_GasDamagePerSecond' => 11,
            'Phase9_RadiusRate' => 0.001,
            'Phase9_SpreadRatio' => 0.5,
            'Phase9_CircleGenerationAlgorithm' => 0,
            'Phase9_LandRatio' => 0.7,
            'RedZoneIsActive' => true,
            'MultiplierRedZoneArea' => 1,
            'MultiplierRedZoneExplosionDensity' => 1,
            'MultiplierRedZoneStartTime' => 1,
            'MultiplierRedZoneEndTime' => 1,
            'MultiplierRedZoneExplosionDelay' => 1,
            'MultiplierRedZoneDuration' => 1,
            'ItemSpawnType' => 'FixedRatioAndAdjustableTotalNumber',
            'ModifiedItemSpawnRatio' => 1,
            'Ammo' => '1',
            'Ammo_12gauge' => '1',
            'Ammo_45acp' => '1',
            'Ammo_556mm' => '1',
            'Ammo_762mm' => '1',
            'Ammo_9mm' => '1',
            'Ammo_bolt' => '1',
            'Ammo_flare' => '1',
            'WSniperRifle' => '1',
            'WSniperRifles_kar98k' => '1',
            'WSniperRifles_m24' => '1',
            'WDMR' => 1,
            'WDMR_mini14' => 1,
            'WDMR_sks' => 1,
            'WDMR_vss' => 1,
            'WDMR_slr' => 1,
            'WDMR_qbu' => 1,
            'WAssaultRifles' => 1,
            'WAssaultRifles_akm' => 1,
            'WAssaultRifles_m416' => 1,
            'WAssaultRifles_m16a4' => 1,
            'WAssaultRifles_scar_l' => 1,
            'WAssaultRifles_qbz95' => 1,
            'WHuntingRifles' => 1,
            'WHuntingRifles_win94' => 1,
            'WLMG' => 1,
            'WLMG_dp28' => 1,
            'WSMG' => 1,
            'WSMG_tommygun' => 1,
            'WSMG_ump' => 1,
            'WSMG_uzi' => 1,
            'WSMG_vector' => 1,
            'Wshotguns' => 1,
            'Wshotguns_s686' => 1,
            'Wshotguns_s12k' => 1,
            'Wshotguns_s1897' => 1,
            'Whandguns' => 1,
            'Whandguns_p18c' => 1,
            'Whandguns_p1911' => 1,
            'Whandguns_p92' => 1,
            'Whandguns_r1895' => 1,
            'Whandguns_r45' => 1,
            'Whandguns_sawedoff' => 1,
            'Wthrowables' => 1,
            'Wthrowables_flashbang' => 1,
            'Wthrowables_fraggrenade' => 1,
            'Wthrowables_molotov' => 1,
            'Wthrowables_smokebomb' => 1,
            'Wmelee' => 1,
            'Wmelee_crowbar' => 1,
            'Wmelee_machete' => 1,
            'Wmelee_pan' => 1,
            'Wmelee_sickle' => 1,
            'Wbow' => 1,
            'Wbow_crossbow' => 1,
            'Wflaregun' => 1,
            'Wflaregun_flaregun' => 1,
            'Ascope' => 1,
            'Ascope_dotsight' => 1,
            'Ascope_holosight' => 1,
            'Ascope_scope2x' => 1,
            'Ascope_scope3x' => 1,
            'Ascope_scope4x' => 1,
            'Ascope_scope6x' => 1,
            'Ascope_scope8x' => 1,
            'Amagazine' => 1,
            'Amagazine_sr_mag' => 1,
            'Amagazine_ar_mag' => 1,
            'Amagazine_smg_mag' => 1,
            'Amagazine_pistol_mag' => 1,
            'Amuzzle' => 1,
            'Amuzzle_sr_muzzle' => 1,
            'Amuzzle_ar_muzzle' => 1,
            'Amuzzle_sg_muzzle' => 1,
            'Amuzzle_smg_muzzle' => 1,
            'Amuzzle_pistol_muzzle' => 1,
            'Aforegrip' => 1,
            'Aforegrip_foregrips' => 1,
            'Astock' => 1,
            'Astock_crossbowquiver' => 1,
            'Astock_ar_composite' => 1,
            'Astock_uzi_stock' => 1,
            'Astock_sg_bulletloops' => 1,
            'Astock_kar98k_bulletloops' => 1,
            'Astock_sr_cheekpad' => 1,
            'Uheal' => 1,
            'Uheal_bandage' => 1,
            'Uheal_firstaid' => 1,
            'Uheal_medkit' => 1,
            'Uboost' => 1,
            'Uboost_energydrink' => 1,
            'Uboost_painkiller' => 1,
            'Uboost_adrenaline' => 1,
            'Ujerrycan' => 1,
            'Ujerrycan_jerrycan' => 1,
            'Ebag' => 1,
            'Ebag_backpack_lv1' => 1,
            'Ebag_backpack_lv2' => 1,
            'Ebag_backpack_lv3' => 1,
            'Ehelmet' => 1,
            'Ehelmet_helmet_lv1' => 1,
            'Ehelmet_helmet_lv2' => 1,
            'Ehelmet_helmet_lv3' => 1,
            'Earmor' => 1,
            'Earmor_armor_lv1' => 1,
            'Earmor_armor_lv2' => 1,
            'Earmor_armor_lv3' => 1,
            'Buggy' => 1,
            'Dacia' => 1,
            'Minibus' => 1,
            'Mirado' => 1,
            'Motorbike' => 1,
            'Motorbike_Sidecar' => 1,
            'PickupTruck' => 1,
            'Uaz' => 1,
            'Boat' => 1,
            'Jetski' => 1,
            'Car' => 1,
            'Esports' => 1,
            'FlareGunIsActive' => true,
            'GeneralItemSpawnCountMultiplierPerCarePackage' => 1,
            'Phase1_AddWhiteZoneCarePackage' => 0,
            'Phase1_AddOutsideZoneCarePackage' => 8,
            'Phase2_AddWhiteZoneCarePackage' => 2,
            'Phase2_AddOutsideZoneCarePackage' => 0,
            'Phase3_AddWhiteZoneCarePackage' => 1,
            'Phase3_AddOutsideZoneCarePackage' => 0,
            'Phase4_AddWhiteZoneCarePackage' => 1,
            'Phase4_AddOutsideZoneCarePackage' => 0,
            'Phase5_AddWhiteZoneCarePackage' => 1,
            'Phase5_AddOutsideZoneCarePackage' => -99,
            'Phase6_AddWhiteZoneCarePackage' => 0,
            'Phase6_AddOutsideZoneCarePackage' => 0,
            'Phase7_AddWhiteZoneCarePackage' => -99,
            'Phase7_AddOutsideZoneCarePackage' => 0,
            'Phase8_AddWhiteZoneCarePackage' => 0,
            'Phase8_AddOutsideZoneCarePackage' => 0,
            'Phase9_AddWhiteZoneCarePackage' => 0,
            'Phase9_AddOutsideZoneCarePackage' => 0,
            'Passcord' => '$password',
        ];
    }

    public function getNormalExecuteArgument(){
        return [
            'IsCustomGame' => true,
            'MapName' => '/Game/Maps/Erangel/Erangel_Main',
            'TeamCount' => 4,
            'MinPlayerCount' => 100,
            'MaxPlayerCount' => 100,
            'StringParameters' => [
                [
                    'First'=>'IsZombie',
                    'Second'=>'false'
                ],
                [
                    'First'=>'MultiplierZombieToZombieDamage',
                    'Second'=>'0'
                ],
                [
                    'First'=>'IsGroggyMode',
                    'Second'=>'true'
                ],
                [
                    'First'=>'ReviveCastingTime',
                    'Second'=>'10'
                ],
                [
                    'First'=>'MultiplierGroggyDamagePerSecond',
                    'Second'=>'1'
                ],
                [
                    'First'=>'Weather',
                    'Second'=>'Clear'
                ],
                [
                    'First'=>'MultiplierRedZone',
                    'Second'=>'undefined'
                ],
                [
                    'First'=>'MultiplierCarePackage',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.0',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.1',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.2',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.3',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.4',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.5',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.6',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.7',
                    'Second'=>'1'
                ],
                [
                    'First' => 'ThingSpawnGroupRatio.13',
                    'Second' => '1'
                ],
                [
                    'First'=>'MultiplierPunchDamage',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Item',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Equip',
                    'Second'=>'1'
                ],
                [
                    'First'=>'RedZoneIsActive',
                    'Second'=>'true'
                ],
                [
                    'First'=>'MultiplierRedZoneArea',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneExplosionDensity',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneStartTime',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneEndTime',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneExplosionDelay',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneDuration',
                    'Second'=>'1'
                ],
                [
                    'First'=>'KillerSpectateMode',
                    'Second'=>'true'
                ],
                [
                    'First' => 'TeamSize',
                    'Second' => '4'
                ],
                [
                    'First' => 'TeamKillDamageModifier',
                    'Second' => '1'
                ],
                [
                    'First'=>'MultiplierBlueZone',
                    'Second'=>'1'
                ],
                [
                    'First'=>'Phase1.StartDelay',
                    'Second'=>'120'
                ],
                [
                    'First'=>'Phase1.WarningDuration',
                    'Second'=>'300'
                ],
                [
                    'First'=>'Phase1.ReleaseDuration',
                    'Second'=>'240'
                ],
                [
                    'First'=>'Phase1.GasDamagePerSecond',
                    'Second'=>'0.4'
                ],
                [
                    'First'=>'Phase1.RadiusRate',
                    'Second'=>'0.4'
                ],
                [
                    'First'=>'Phase1.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase1.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase2.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase2.WarningDuration',
                    'Second'=>'200'
                ],
                [
                    'First'=>'Phase2.ReleaseDuration',
                    'Second'=>'140'
                ],
                [
                    'First'=>'Phase2.GasDamagePerSecond',
                    'Second'=>'0.6'
                ],
                [
                    'First'=>'Phase2.RadiusRate',
                    'Second'=>'0.65'
                ],
                [
                    'First'=>'Phase2.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase2.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase3.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase3.WarningDuration',
                    'Second'=>'150'
                ],
                [
                    'First'=>'Phase3.ReleaseDuration',
                    'Second'=>'90'
                ],
                [
                    'First'=>'Phase3.GasDamagePerSecond',
                    'Second'=>'0.8'
                ],
                [
                    'First'=>'Phase3.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase3.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase3.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase4.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase4.WarningDuration',
                    'Second'=>'120'
                ],
                [
                    'First'=>'Phase4.ReleaseDuration',
                    'Second'=>'60'
                ],
                [
                    'First'=>'Phase4.GasDamagePerSecond',
                    'Second'=>'1'
                ],
                [
                    'First'=>'Phase4.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase4.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase4.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase5.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase5.WarningDuration',
                    'Second'=>'120'
                ],
                [
                    'First'=>'Phase5.ReleaseDuration',
                    'Second'=>'40'
                ],
                [
                    'First'=>'Phase5.GasDamagePerSecond',
                    'Second'=>'3'
                ],
                [
                    'First'=>'Phase5.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase5.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase5.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase6.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase6.WarningDuration',
                    'Second'=>'90'
                ],
                [
                    'First'=>'Phase6.ReleaseDuration',
                    'Second'=>'30'
                ],
                [
                    'First'=>'Phase6.GasDamagePerSecond',
                    'Second'=>'5'
                ],
                [
                    'First'=>'Phase6.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase6.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase6.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase7.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase7.WarningDuration',
                    'Second'=>'90'
                ],
                [
                    'First'=>'Phase7.ReleaseDuration',
                    'Second'=>'30'
                ],
                [
                    'First'=>'Phase7.GasDamagePerSecond',
                    'Second'=>'7'
                ],
                [
                    'First'=>'Phase7.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase7.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase7.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase8.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase8.WarningDuration',
                    'Second'=>'60'
                ],
                [
                    'First'=>'Phase8.ReleaseDuration',
                    'Second'=>'60'
                ],
                [
                    'First'=>'Phase8.GasDamagePerSecond',
                    'Second'=>'9'
                ],
                [
                    'First'=>'Phase8.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase8.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase8.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase9.StartDelay',
                    'Second'=>'180'
                ],
                [
                    'First'=>'Phase9.WarningDuration',
                    'Second'=>'10'
                ],
                [
                    'First'=>'Phase9.ReleaseDuration',
                    'Second'=>'30'
                ],
                [
                    'First'=>'Phase9.GasDamagePerSecond',
                    'Second'=>'11'
                ],
                [
                    'First'=>'Phase9.RadiusRate',
                    'Second'=>'0.001'
                ],
                [
                    'First'=>'Phase9.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase9.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'DisplayEndCircleLocation',
                    'Second'=>'false'
                ],
                [
                    'First'=>'EndCircleLocationRate',
                    'Second'=>'0'
                ],
                [
                    'First'=>'EndCircleLocationArea1',
                    'Second'=>'1'
                ],
                [
                    'First'=>'EndCircleLocationArea2',
                    'Second'=>'1'
                ],
                [
                    'First'=>'EndCircleLocationArea3',
                    'Second'=>'1'
                ],
                [
                    "First" => "ItemSpawnType",
                    "Second" => "FixedRatioAndAdjustableTotalNumber"
                ],
                [
                    "First" => "ModifiedItemSpawnRatio",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ammo",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.12gauge",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.45acp",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.556mm",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.762mm",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.9mm",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.bolt",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.flare",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WSniperRifles",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.kar98k",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.m24",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WDMR",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.mini14",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sks",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.vss",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.slr",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.qbu",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WAssaultRifles",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.akm",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.m416",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.m16a4",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scar_l",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.qbz95",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WHuntingRifles",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.win94",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WLMG",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.dp28",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WSMG",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.tommygun",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.ump",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.uzi",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.vector",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wshotguns",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.s686",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.s12k",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.s1897",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Whandguns",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.p18c",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.p1911",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.p92",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.r1895",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.r45",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sawedoff",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wthrowables",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.flashbang",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.fraggrenade",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.molotov",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.smokebomb",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wmelee",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.crowbar",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.machete",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.pan",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sickle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wbow",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.crossbow",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wflaregun",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.flaregun",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ascope",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.dotsight",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.holosight",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope2x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope3x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope4x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope6x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope8x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Amagazine",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sr_mag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.ar_mag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.smg_mag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.pistol_mag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Amuzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sr_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.ar_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sg_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.smg_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.pistol_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Aforegrip",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.foregrips",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Astock",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.crossbowquiver",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.ar_composite",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.uzi_stock",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sg_bulletloops",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.kar98k_bulletloops",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sr_cheekpad",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Uheal",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.bandage",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.firstaid",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.medkit",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Uboost",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.energydrink",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.painkiller",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.adrenaline",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ujerrycan",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.jerrycan",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ebag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.backpack_lv1",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.backpack_lv2",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.backpack_lv3",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ehelmet",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.helmet_lv1",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.helmet_lv2",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.helmet_lv3",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Earmor",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.armor_lv1",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.armor_lv2",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.armor_lv3",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Buggy",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Dacia",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Minibus",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Mirado",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Motorbike",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Motorbike_Sidecar",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.PickupTruck",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Uaz",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Esports",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Boat",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Jetski",
                    "Second" => "1"
                ],
                [
                    "First" => "FlareGunIsActive",
                    "Second" => "true"
                ],
                [
                    "First" => "GeneralItemSpawnCountMultiplierPerCarePackage",
                    "Second" => "1"
                ],
                [
                    "First" => "Phase1.AddWhiteZoneCarepackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase2.AddWhiteZoneCarepackage",
                    "Second" => "2"
                ],
                [
                    "First" => "Phase3.AddWhiteZoneCarepackage",
                    "Second" => "1"
                ],
                [
                    "First" => "Phase4.AddWhiteZoneCarepackage",
                    "Second" => "1"
                ],
                [
                    "First" => "Phase5.AddWhiteZoneCarepackage",
                    "Second" => "1"
                ],
                [
                    "First" => "Phase6.AddWhiteZoneCarepackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase7.AddWhiteZoneCarepackage",
                    "Second" => "-99"
                ],
                [
                    "First" => "Phase8.AddWhiteZoneCarepackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase9.AddWhiteZoneCarepackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase1.AddOutsideZoneCarePackage",
                    "Second" => "8"
                ],
                [
                    "First" => "Phase2.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase3.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase4.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase5.AddOutsideZoneCarePackage",
                    "Second" => "-99"
                ],
                [
                    "First" => "Phase6.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase7.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase8.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase9.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "CameraViewBehaviour",
                    "Second" => "FpsAndTps"
                ],

            ]
        ];
    }

    public function getParamsString()
    {
        return [
            'Car',
            'Boat',
            'CarePackageFreq',
            'WSniperRifle',
            'WAssaultRifle',
            'WHuntingRifle',
            'WLMG',
            'WSMG',
            'WShotGun',
            'WPistol',
            'WThrowWeapon',
            'WMelee',
            'Wetc',
            'WFlaregun',
            'WDMR',
            'AScope',
            'AMagazine',
            'AMuzzle',
            'AStockforegrip',
            'MedKit',
            'FirstAid',
            'Bandage',
            'PainKiller',
            'EnergyDrink',
            'JerryCan',
            'Bag_Lv1',
            'Bag_Lv2',
            'Bag_Lv3',
            'Helmet_Lv1',
            'Helmet_Lv2',
            'Helmet_Lv3',
            'Armor_Lv1',
            'Armor_Lv2',
            'Armor_Lv3',
            'Costume',
            'Ammo',
        ];
    }

    public function getZombieExecuteArgument(){
        return [
            'IsCustomGame' => true,
            'MapName' => '/Game/Maps/Erangel/Erangel_Main',
            'TeamCount' => 4,
            'MinPlayerCount' => 100,
            'MaxPlayerCount' => 100,
            'StringParameters' => [
                [
                    'First'=>'IsZombie',
                    'Second'=>'false'
                ],
                [
                    'First'=>'MultiplierZombieToZombieDamage',
                    'Second'=>'0'
                ],
                [
                    'First'=>'IsGroggyMode',
                    'Second'=>'true'
                ],
                [
                    'First'=>'ReviveCastingTime',
                    'Second'=>'10'
                ],
                [
                    'First'=>'MultiplierGroggyDamagePerSecond',
                    'Second'=>'1'
                ],
                [
                    'First'=>'Weather',
                    'Second'=>'Clear'
                ],
                [
                    'First'=>'MultiplierRedZone',
                    'Second'=>'undefined'
                ],
                [
                    'First'=>'MultiplierCarePackage',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.0',
                    'Second'=>'0'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.1',
                    'Second'=>'0'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.2',
                    'Second'=>'0'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.3',
                    'Second'=>'0'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.4',
                    'Second'=>'0'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.5',
                    'Second'=>'0'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.6',
                    'Second'=>'0'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.7',
                    'Second'=>'0'
                ],
                [
                    'First'=>'MultiplierPunchDamage',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.WSniperRifle',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.WAssaultRifle',
                    'Second'=>'1.5'
                ],
                [
                    'First'=>'ItemSpawnCategory.WHuntingRifle',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.WLMG',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.WSMG',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.WShotGun',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.WPistol',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.WThrowWeapon',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.WMelee',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Wetc',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.WFlaregun',
                    'Second'=>'0'
                ],
                [
                    'First'=>'ItemSpawnCategory.WDMR',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.AScope',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.AMagazine',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.AMuzzle',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.AStock&foregrip',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Item',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Equip',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Costume',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Ammo',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.MedKit',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.FirstAid',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Bandage',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.PainKiller',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.EnergyDrink',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.JerryCan',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Bag_Lv1',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Bag_Lv2',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Bag_Lv3',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Helmet_Lv1',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Helmet_Lv2',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Helmet_Lv3',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Armor_Lv1',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Armor_Lv2',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Armor_Lv3',
                    'Second'=>'1'
                ],
                [
                    'First'=>'RedZoneIsActive',
                    'Second'=>'true'
                ],
                [
                    'First'=>'KillerSpectateMode',
                    'Second'=>'true'
                ],
                [
                    'First' => 'TeamSize',
                    'Second' => '1'
                ],
                [
                    'First' => 'TeamKillDamageModifier',
                    'Second' => '1'
                ],
                [
                    'First' => 'CameraViewBehaviour',
                    'Second' => 'FpsAndTps'
                ],
                [
                    'First'=>'MultiplierBlueZone',
                    'Second'=>'1'
                ],
                [
                    'First'=>'Phase1.StartDelay',
                    'Second'=>'120'
                ],
                [
                    'First'=>'Phase1.WarningDuration',
                    'Second'=>'300'
                ],
                [
                    'First'=>'Phase1.ReleaseDuration',
                    'Second'=>'240'
                ],
                [
                    'First'=>'Phase1.GasDamagePerSecond',
                    'Second'=>'0.4'
                ],
                [
                    'First'=>'Phase1.RadiusRate',
                    'Second'=>'0.4'
                ],
                [
                    'First'=>'Phase1.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase1.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase2.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase2.WarningDuration',
                    'Second'=>'200'
                ],
                [
                    'First'=>'Phase2.ReleaseDuration',
                    'Second'=>'140'
                ],
                [
                    'First'=>'Phase2.GasDamagePerSecond',
                    'Second'=>'0.6'
                ],
                [
                    'First'=>'Phase2.RadiusRate',
                    'Second'=>'0.65'
                ],
                [
                    'First'=>'Phase2.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase2.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase3.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase3.WarningDuration',
                    'Second'=>'150'
                ],
                [
                    'First'=>'Phase3.ReleaseDuration',
                    'Second'=>'90'
                ],
                [
                    'First'=>'Phase3.GasDamagePerSecond',
                    'Second'=>'0.8'
                ],
                [
                    'First'=>'Phase3.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase3.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase3.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase4.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase4.WarningDuration',
                    'Second'=>'120'
                ],
                [
                    'First'=>'Phase4.ReleaseDuration',
                    'Second'=>'60'
                ],
                [
                    'First'=>'Phase4.GasDamagePerSecond',
                    'Second'=>'1'
                ],
                [
                    'First'=>'Phase4.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase4.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase4.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase5.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase5.WarningDuration',
                    'Second'=>'120'
                ],
                [
                    'First'=>'Phase5.ReleaseDuration',
                    'Second'=>'40'
                ],
                [
                    'First'=>'Phase5.GasDamagePerSecond',
                    'Second'=>'3'
                ],
                [
                    'First'=>'Phase5.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase5.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase5.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase6.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase6.WarningDuration',
                    'Second'=>'90'
                ],
                [
                    'First'=>'Phase6.ReleaseDuration',
                    'Second'=>'30'
                ],
                [
                    'First'=>'Phase6.GasDamagePerSecond',
                    'Second'=>'5'
                ],
                [
                    'First'=>'Phase6.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase6.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase6.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase7.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase7.WarningDuration',
                    'Second'=>'90'
                ],
                [
                    'First'=>'Phase7.ReleaseDuration',
                    'Second'=>'30'
                ],
                [
                    'First'=>'Phase7.GasDamagePerSecond',
                    'Second'=>'7'
                ],
                [
                    'First'=>'Phase7.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase7.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase7.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase8.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase8.WarningDuration',
                    'Second'=>'60'
                ],
                [
                    'First'=>'Phase8.ReleaseDuration',
                    'Second'=>'60'
                ],
                [
                    'First'=>'Phase8.GasDamagePerSecond',
                    'Second'=>'9'
                ],
                [
                    'First'=>'Phase8.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase8.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase8.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase9.StartDelay',
                    'Second'=>'180'
                ],
                [
                    'First'=>'Phase9.WarningDuration',
                    'Second'=>'10'
                ],
                [
                    'First'=>'Phase9.ReleaseDuration',
                    'Second'=>'30'
                ],
                [
                    'First'=>'Phase9.GasDamagePerSecond',
                    'Second'=>'11'
                ],
                [
                    'First'=>'Phase9.RadiusRate',
                    'Second'=>'0.001'
                ],
                [
                    'First'=>'Phase9.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase9.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'DisplayEndCircleLocation',
                    'Second'=>'false'
                ],
                [
                    'First'=>'EndCircleLocationRate',
                    'Second'=>'0'
                ],
                [
                    'First'=>'EndCircleLocationArea1',
                    'Second'=>'1'
                ],
                [
                    'First'=>'EndCircleLocationArea2',
                    'Second'=>'1'
                ],
                [
                    'First'=>'EndCircleLocationArea3',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneArea',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneExplosionDensity',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneStartTime',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneEndTime',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneExplosionDelay',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneDuration',
                    'Second'=>'1'
                ],
                [
                    'First' => 'ZombieCameraViewBehaviour',
                    'Second' => 'TpsOnly'
                ],
            ]
        ];
    }

    public function getWarExecuteArgument(){
        return [
            'IsCustomGame' => true,
            'MapName' => '/Game/Maps/Erangel/Erangel_Main',
            'TeamCount' => 8,
            'MinPlayerCount' => 24,
            'MaxPlayerCount' => 24,
            'StringParameters' => [
                [
                    'First'=>'IsZombie',
                    'Second'=>'false'
                ],
                [
                    'First'=>'MultiplierZombieToZombieDamage',
                    'Second'=>'0'
                ],
                [
                    'First'=>'IsGroggyMode',
                    'Second'=>'true'
                ],
                [
                    'First'=>'ReviveCastingTime',
                    'Second'=>'10'
                ],
                [
                    'First'=>'MultiplierGroggyDamagePerSecond',
                    'Second'=>'1'
                ],
                [
                    'First'=>'Weather',
                    'Second'=>'Clear'
                ],
                [
                    'First'=>'MultiplierRedZone',
                    'Second'=>'undefined'
                ],
                [
                    'First'=>'MultiplierCarePackage',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.0',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.1',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.2',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.3',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.4',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.5',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.6',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ThingSpawnGroupRatio.7',
                    'Second'=>'1'
                ],
                [
                    'First' => 'ThingSpawnGroupRatio.13',
                    'Second' => '1'
                ],
                [
                    'First'=>'MultiplierPunchDamage',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Item',
                    'Second'=>'1'
                ],
                [
                    'First'=>'ItemSpawnCategory.Equip',
                    'Second'=>'1'
                ],
                [
                    'First'=>'RedZoneIsActive',
                    'Second'=>'true'
                ],
                [
                    'First'=>'MultiplierRedZoneArea',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneExplosionDensity',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneStartTime',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneEndTime',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneExplosionDelay',
                    'Second'=>'1'
                ],
                [
                    'First'=>'MultiplierRedZoneDuration',
                    'Second'=>'1'
                ],
                [
                    'First'=>'KillerSpectateMode',
                    'Second'=>'true'
                ],
                [
                    'First' => 'TeamSize',
                    'Second' => '4'
                ],
                [
                    'First' => 'TeamKillDamageModifier',
                    'Second' => '1'
                ],
                [
                    'First'=>'MultiplierBlueZone',
                    'Second'=>'1'
                ],
                [
                    'First'=>'Phase1.StartDelay',
                    'Second'=>'120'
                ],
                [
                    'First'=>'Phase1.WarningDuration',
                    'Second'=>'300'
                ],
                [
                    'First'=>'Phase1.ReleaseDuration',
                    'Second'=>'240'
                ],
                [
                    'First'=>'Phase1.GasDamagePerSecond',
                    'Second'=>'0.4'
                ],
                [
                    'First'=>'Phase1.RadiusRate',
                    'Second'=>'0.4'
                ],
                [
                    'First'=>'Phase1.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase1.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase2.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase2.WarningDuration',
                    'Second'=>'200'
                ],
                [
                    'First'=>'Phase2.ReleaseDuration',
                    'Second'=>'140'
                ],
                [
                    'First'=>'Phase2.GasDamagePerSecond',
                    'Second'=>'0.6'
                ],
                [
                    'First'=>'Phase2.RadiusRate',
                    'Second'=>'0.65'
                ],
                [
                    'First'=>'Phase2.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase2.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase3.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase3.WarningDuration',
                    'Second'=>'150'
                ],
                [
                    'First'=>'Phase3.ReleaseDuration',
                    'Second'=>'90'
                ],
                [
                    'First'=>'Phase3.GasDamagePerSecond',
                    'Second'=>'0.8'
                ],
                [
                    'First'=>'Phase3.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase3.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase3.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase4.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase4.WarningDuration',
                    'Second'=>'120'
                ],
                [
                    'First'=>'Phase4.ReleaseDuration',
                    'Second'=>'60'
                ],
                [
                    'First'=>'Phase4.GasDamagePerSecond',
                    'Second'=>'1'
                ],
                [
                    'First'=>'Phase4.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase4.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase4.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase5.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase5.WarningDuration',
                    'Second'=>'120'
                ],
                [
                    'First'=>'Phase5.ReleaseDuration',
                    'Second'=>'40'
                ],
                [
                    'First'=>'Phase5.GasDamagePerSecond',
                    'Second'=>'3'
                ],
                [
                    'First'=>'Phase5.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase5.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase5.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase6.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase6.WarningDuration',
                    'Second'=>'90'
                ],
                [
                    'First'=>'Phase6.ReleaseDuration',
                    'Second'=>'30'
                ],
                [
                    'First'=>'Phase6.GasDamagePerSecond',
                    'Second'=>'5'
                ],
                [
                    'First'=>'Phase6.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase6.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase6.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase7.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase7.WarningDuration',
                    'Second'=>'90'
                ],
                [
                    'First'=>'Phase7.ReleaseDuration',
                    'Second'=>'30'
                ],
                [
                    'First'=>'Phase7.GasDamagePerSecond',
                    'Second'=>'7'
                ],
                [
                    'First'=>'Phase7.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase7.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase7.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase8.StartDelay',
                    'Second'=>'0'
                ],
                [
                    'First'=>'Phase8.WarningDuration',
                    'Second'=>'60'
                ],
                [
                    'First'=>'Phase8.ReleaseDuration',
                    'Second'=>'60'
                ],
                [
                    'First'=>'Phase8.GasDamagePerSecond',
                    'Second'=>'9'
                ],
                [
                    'First'=>'Phase8.RadiusRate',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase8.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase8.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'Phase9.StartDelay',
                    'Second'=>'180'
                ],
                [
                    'First'=>'Phase9.WarningDuration',
                    'Second'=>'10'
                ],
                [
                    'First'=>'Phase9.ReleaseDuration',
                    'Second'=>'30'
                ],
                [
                    'First'=>'Phase9.GasDamagePerSecond',
                    'Second'=>'11'
                ],
                [
                    'First'=>'Phase9.RadiusRate',
                    'Second'=>'0.001'
                ],
                [
                    'First'=>'Phase9.SpreadRatio',
                    'Second'=>'0.5'
                ],
                [
                    'First'=>'Phase9.LandRatio',
                    'Second'=>'0.7'
                ],
                [
                    'First'=>'DisplayEndCircleLocation',
                    'Second'=>'false'
                ],
                [
                    'First'=>'EndCircleLocationRate',
                    'Second'=>'0'
                ],
                [
                    'First'=>'EndCircleLocationArea1',
                    'Second'=>'1'
                ],
                [
                    'First'=>'EndCircleLocationArea2',
                    'Second'=>'1'
                ],
                [
                    'First'=>'EndCircleLocationArea3',
                    'Second'=>'1'
                ],
                [
                    "First" => "ItemSpawnType",
                    "Second" => "FixedRatioAndAdjustableTotalNumber"
                ],
                [
                    "First" => "ModifiedItemSpawnRatio",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ammo",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.12gauge",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.45acp",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.556mm",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.762mm",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.9mm",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.bolt",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.flare",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WSniperRifles",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.kar98k",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.m24",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WDMR",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.mini14",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sks",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.vss",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.slr",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.qbu",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WAssaultRifles",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.akm",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.m416",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.m16a4",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scar_l",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.qbz95",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WHuntingRifles",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.win94",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WLMG",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.dp28",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.WSMG",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.tommygun",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.ump",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.uzi",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.vector",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wshotguns",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.s686",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.s12k",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.s1897",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Whandguns",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.p18c",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.p1911",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.p92",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.r1895",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.r45",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sawedoff",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wthrowables",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.flashbang",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.fraggrenade",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.molotov",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.smokebomb",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wmelee",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.crowbar",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.machete",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.pan",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sickle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wbow",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.crossbow",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Wflaregun",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.flaregun",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ascope",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.dotsight",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.holosight",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope2x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope3x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope4x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope6x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.scope8x",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Amagazine",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sr_mag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.ar_mag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.smg_mag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.pistol_mag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Amuzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sr_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.ar_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sg_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.smg_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.pistol_muzzle",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Aforegrip",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.foregrips",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Astock",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.crossbowquiver",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.ar_composite",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.uzi_stock",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sg_bulletloops",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.kar98k_bulletloops",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.sr_cheekpad",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Uheal",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.bandage",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.firstaid",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.medkit",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Uboost",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.energydrink",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.painkiller",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.adrenaline",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ujerrycan",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.jerrycan",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ebag",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.backpack_lv1",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.backpack_lv2",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.backpack_lv3",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Ehelmet",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.helmet_lv1",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.helmet_lv2",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.helmet_lv3",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnCategory.Earmor",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.armor_lv1",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.armor_lv2",
                    "Second" => "1"
                ],
                [
                    "First" => "ItemSpawnSubCategory.armor_lv3",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Buggy",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Dacia",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Minibus",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Mirado",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Motorbike",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Motorbike_Sidecar",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.PickupTruck",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Uaz",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Esports",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Boat",
                    "Second" => "1"
                ],
                [
                    "First" => "VehicleSpawnSubCategory.Jetski",
                    "Second" => "1"
                ],
                [
                    "First" => "FlareGunIsActive",
                    "Second" => "true"
                ],
                [
                    "First" => "GeneralItemSpawnCountMultiplierPerCarePackage",
                    "Second" => "1"
                ],
                [
                    "First" => "Phase1.AddWhiteZoneCarepackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase2.AddWhiteZoneCarepackage",
                    "Second" => "2"
                ],
                [
                    "First" => "Phase3.AddWhiteZoneCarepackage",
                    "Second" => "1"
                ],
                [
                    "First" => "Phase4.AddWhiteZoneCarepackage",
                    "Second" => "1"
                ],
                [
                    "First" => "Phase5.AddWhiteZoneCarepackage",
                    "Second" => "1"
                ],
                [
                    "First" => "Phase6.AddWhiteZoneCarepackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase7.AddWhiteZoneCarepackage",
                    "Second" => "-99"
                ],
                [
                    "First" => "Phase8.AddWhiteZoneCarepackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase9.AddWhiteZoneCarepackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase1.AddOutsideZoneCarePackage",
                    "Second" => "8"
                ],
                [
                    "First" => "Phase2.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase3.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase4.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase5.AddOutsideZoneCarePackage",
                    "Second" => "-99"
                ],
                [
                    "First" => "Phase6.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase7.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase8.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    "First" => "Phase9.AddOutsideZoneCarePackage",
                    "Second" => "0"
                ],
                [
                    'First' => 'GoalScore',
                    'Second' => '150'
                ],
                [
                    'First' => 'TimeLimit',
                    'Second' => '900'
                ],
                [
                    'First' => 'TeamElimination',
                    'Second' => 'false'
                ],
                [
                    'First' => 'HealthByRevive',
                    'Second' => '50'
                ],
                [
                    'First' => 'UseWarRoyaleBluezone',
                    'Second' => 'false'
                ],
                [
                    'First' => 'StaticBlueZoneSize',
                    'Second' => '0.05'
                ],
                [
                    'First' => 'RespawnType',
                    'Second' => 'AIR'
                ],
                [
                    'First' => 'RespawnPeriod',
                    'Second' => '40'
                ],
                [
                    'First' => 'RespawnKit',
                    'Second' => 'RespawnKit_CQB'
                ],
                [
                    'First' => 'CarePackageType',
                    'Second' => 'CarepackageKit_Basic'
                ],
                [
                    'First' => 'CarePackageNextSpawnTime',
                    'Second' => '90'
                ],
                [
                    'First' => 'GroggyDamagePerSecond',
                    'Second' => '5'
                ],
                [
                    'First' => 'RespawnOffTimeLeftRatio',
                    'Second' => '0'
                ],
                [
                    "First" => "CameraViewBehaviour",
                    "Second" => "FpsAndTps"
                ],
            ]
        ];
    }

    public function getWeapon(){
        return [
            'kar98k' => 'WSniperRifles_',
            'm24' => 'WSniperRifles_',
            'mini14' => 'WDMR_',
            'sks' => 'WDMR_',
            'vss' => 'WDMR_',
            'slr' => 'WDMR_',
            'qbu' => 'WDMR_',
            'akm' => 'WAssaultRifles_',
            'm416' => 'WAssaultRifles_',
            'm16a4' => 'WAssaultRifles_',
            'scar_l' => 'WAssaultRifles_',
            'qbz95' => 'WAssaultRifles_',
            'win94' => 'WHuntingRifles_',
            'dp28' => 'WLMG_',
            'tommygun' => 'WSMG_',
            'ump' => 'WSMG_',
            'uzi' => 'WSMG_',
            'vector' => 'WSMG_',
            's686' => 'Wshotguns_',
            's12k' => 'Wshotguns_',
            's1897' => 'Wshotguns_',
            'p18c' => 'Whandguns_',
            'p1911' => 'Whandguns_',
            'p92' => 'Whandguns_',
            'r1895' => 'Whandguns_',
            'r45' => 'Whandguns_',
            'sawedoff' => 'Whandguns_',
            'flashbang' => 'Wthrowables_',
            'fraggrenade' => 'Wthrowables_',
            'molotov' => 'Wthrowables_',
            'smokebomb' => 'Wthrowables_',
            'crowbar' => 'Wmelee_',
            'machete' => 'Wmelee_',
            'pan' => 'Wmelee_',
            'sickle' => 'Wmelee_',
            'crossbow' => 'Wbow_',
            'dotsight' => 'Ascope_',
            'holosight' => 'Ascope_',
            'scope2x' => 'Ascope_',
            'scope3x' => 'Ascope_',
            'scope4x' => 'Ascope_',
            'scope6x' => 'Ascope_',
            'scope8x' => 'Ascope_',
            'ar_mag' => 'Amagazine_',
            'sr_mag' => 'Amagazine_',
            'smg_mag' => 'Amagazine_',
            'pistol_mag' => 'Amagazine_',
            'sr_muzzle' => 'Amuzzle_',
            'ar_muzzle' => 'Amuzzle_',
            'sg_muzzle' => 'Amuzzle_',
            'smg_muzzle' => 'Amuzzle_',
            'pistol_muzzle' => 'Amuzzle_',
            'crossbowquiver' => 'Astock_',
            'ar_composite' => 'Astock_',
            'uzi_stock' => 'Astock_',
            'sg_bulletloops' => 'Astock_',
            'kar98k_bulletloops' => 'Astock_',
            'sr_cheekpad' => 'Astock_',
            'bandage' => 'Uheal_',
            'firstaid' => 'Uheal_',
            'medkit' => 'Uheal_',
            'energydrink' => 'Uboost_',
            'painkiller' => 'Uboost_',
            'adrenaline' => 'Uboost_',
            'backpack_lv1' => 'Ebag_',
            'backpack_lv2' => 'Ebag_',
            'backpack_lv3' => 'Ebag_',
            'helmet_lv1' => 'Ehelmet_',
            'helmet_lv2' => 'Ehelmet_',
            'helmet_lv3' => 'Ehelmet_',
            'armor_lv1' => 'Earmor_',
            'armor_lv2' => 'Earmor_',
            'armor_lv3' => 'Earmor_',
            'Buggy' => 'Buggy',
            'Dacia' => 'Dacia',
            'Minibus' => 'Minibus',
            'Mirado' => 'Mirado',
            'Motorbike' => 'Motorbike',
            'Motorbike_Sidecar' => 'Motorbike_Sidecar',
            'PickupTruck' => 'PickupTruck',
            'Uaz' => 'Uaz',
            'Boat' => 'Boat',
            'Jetski' => 'Jetski',
            'Car' => 'Car',
            '12gauge' => 'Ammo_',
            '45acp' => 'Ammo_',
            '556mm' => 'Ammo_',
            '762mm' => 'Ammo_',
            '9mm' => 'Ammo_',
            'bolt' => 'Ammo_',
            'flare' => 'Ammo_',
            'flaregun' => 'Wflaregun_',
            'jerrycan' => 'Ujerrycan_',
            'foregrips' => 'Aforegrip_',
        ];
    }
}
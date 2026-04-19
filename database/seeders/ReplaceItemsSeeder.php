<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReplaceItemsSeeder extends Seeder
{
    public function run(): void
    {
        // ── Wipe all dependent records first (foreign key safe order) ──
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('borrow_entries')->truncate();
        DB::table('usage_logs')->truncate();
        DB::table('stock_entries')->truncate();
        DB::table('borrows')->truncate();
        DB::table('transfers')->truncate();
        DB::table('disposals')->truncate();
        DB::table('items')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ── Categories derived from item types ──
        $consumableCat = Category::create(['name' => 'Consumable', 'description' => 'Single-use and expendable supplies']);
        $deviceCat     = Category::create(['name' => 'Device',     'description' => 'Reusable equipment and hardware']);

        // ── Item list ──
        // Format: [description, type, brand, model]
        $items = [
            ['PIGTAIL LC/PC OM3 (1m)',                          'consumable', null,          'OM3 LC/PC'],
            ['FIBER OPTIC CABLE (30m)',                          'consumable', 'ONTI',        'OM4-DX LC-LC'],
            ['FIBER OPTIC CABLE (50m)',                          'consumable', 'ONTI',        'OM4-DX LC-LC'],
            ['FIBER OPTIC CABLE (100M)',                         'consumable', 'ONTI',        'OM4-DX LC-LC'],
            ['FIBER OPTIC CONNECTOR',                            'consumable', 'NONE',        'LC-LC/DX'],
            ['FIBER OPTIC PATCH CORD (2m)',                      'consumable', null,          'OM4-DX-LC-LC'],
            ['FIBER OPTIC PATCH CORD (5m)',                      'consumable', null,          'OM4-DX-LC-LC'],
            ['BOND PAPER (LEGAL SIZE)',                          'consumable', 'OFFICE PRO',  'LEGAL SIZE'],
            ['BOND PAPER (A4 SIZE)',                             'consumable', 'OFFICE PRO',  'A4 SIZE'],
            ['MULTIPURPOSE PAPER',                              'consumable', 'PAPERLINE',   'A4 SIZE'],
            ['STICKER PAPER (GLOSSY)',                           'consumable', 'I-TECH',      'A4 GLOSSY'],
            ['FILE FOLDER (A4)',                                 'consumable', 'SYSTEM',      'A4 SIZE'],
            ['FILE FOLDER (LEGAL)',                              'consumable', 'SYSTEM',      'LEGAL SIZE'],
            ['STICKER PAPER (MATTE)',                            'consumable', 'JOY',         'A4 MATTE'],
            ['USB MOUSE',                                        'consumable', 'LENOVO',      'NB-0009'],
            ['CHARGER',                                          'device',     'MAKITA',      'BL1830B'],
            ['4-PORT KVM SWITCH',                                'device',     'ATEN',        'CS84U'],
            ['TRANSMISSION LINE',                                'consumable', 'HDTV',        'HDTV PREMIUM 10 METERS'],
            ['WIRELESS MIC',                                     'device',     'JBL',         'PARTY BOX'],
            ['SPEAKER',                                          'device',     'JBL',         'PARTYBOX710'],
            ['ATX POWER SUPPLY',                                 'device',     'CORSAIR',     'CV550'],
            ['NETWORK SWITCH',                                   'device',     'UNIFI',       'POE+'],
            ['UPS (APC SMART)',                                  'device',     'APC',         'SMART UPS'],
            ['UPS (APC EASY)',                                   'device',     'APC',         'EASY UPS'],
            ['UPS (INPLAY)',                                     'device',     'INPLAY',      'IP-UPS-650VA'],
            ['UPS (ACCUPOWER)',                                  'device',     'ACCUPOWER',   'SMD650-P'],
            ['POINT TO POINT WIRELESS AP ANTENNA',              'device',     'LIFEBEAM',    'M5'],
            ['8 PORT SWITCH',                                    'device',     'TP-LINK',     'TL-SG108PE'],
            ['NETWORK SWITCH (CISCO)',                           'device',     'CISCO',       'N/A'],
            ['8 PORT MANAGED SWITCH',                            'device',     'TP-LINK',     'SG3210'],
            ['SMART MANAGED SWITCH',                             'device',     'HIKVISION',   'DS-3E1326P-EI'],
            ['CONSOLE MANAGER',                                  'device',     'LENOVO',      '1754HC3'],
            ['MULTIMEDIA PROJECTOR',                             'device',     'EPSON',       'EB-E12'],
            ['PRINTER (KYOCERA)',                                'device',     'KYOCERA',     'ECOSYS P2040DN'],
            ['PRINTER (EPSON ECOTANK)',                          'device',     'EPSON',       'ECOTANK L3210'],
            ['CARRIAGE IMPACT PRINTER',                          'device',     'EPSON',       'LQ-310'],
            ['THERMAL RECEIPT PRINTER',                          'device',     'EPSON',       'TM-T88VII'],
            ['POWER DISTRIBUTION UNIT',                          'device',     'AMPCOM',      'N/A'],
            ['DISPLAY MONITOR (VIEWSONIC)',                      'device',     'VIEWSONIC',   'VA22E1-H'],
            ['DISPLAY MONITOR (ACER EK251Q)',                    'device',     'ACER',        'EK251Q'],
            ['DISPLAY MONITOR (ACER V247Y)',                     'device',     'ACER',        'V247Y'],
            ['DISPLAY MONITOR (XITRIX)',                         'device',     'XITRIX',      'E40 MICRO PC'],
            ['NETWORK CAMERA',                                   'device',     'ALHUA',       'DH-IPC'],
            ['IR TURRET CAMERA',                                 'device',     'HIKVISION',   'DS-IPF'],
            ['KIT',                                              'consumable', 'MAKITA',      'N/A'],
            ['BROWN ENVELOPE (SHORT)',                           'consumable', 'N/A',         'SHORT'],
            ['BROWN ENVELOPE (LONG)',                            'consumable', 'N/A',         'LONG'],
            ['BLACK CLEARBOOK FOLDER (LONG)',                    'consumable', 'N/A',         'LONG'],
            ['BLACK CLEARBOOK FOLDER (SHORT)',                   'consumable', 'N/A',         'SHORT'],
            ['CONSOLE MANAGER/SWITCH',                           'device',     'LENOVO',      '1754HC3'],
            ['IP PHONE',                                         'device',     'GRANDSTREAM', 'GXP1630'],
            ['CORDED PHONE',                                     'device',     'CALLER ID',   'KX-T219CID'],
            ['SINGLE LINE TELEPHONE',                            'device',     'NEC',         'AT-40'],
            ['MINI KEYPAD NUMERIC',                              'consumable', 'A4TECH',      'TK-5'],
            ['MOUSE PAD',                                        'consumable', 'XITRIX',      'N/A'],
            ['CAT6E CONNECTOR',                                  'consumable', 'AD-LINK',     'RJ6C1H'],
            ['PAPER CLIPS',                                      'consumable', 'NONE',        'NONE'],
            ['CABLES AND CD-R',                                  'consumable', 'RANDOM',      'RANDOM'],
            ['PATCHED CABLE',                                    'consumable', 'NONE',        'NONE'],
            ['STAPLER',                                          'consumable', 'NONE',        'NONE'],
            ['RUBBER FEEDER FOR EPSON L301',                     'consumable', 'NONE',        'NONE'],
            ['RUBBER FEEDER FOR EPSON L311',                     'consumable', 'NONE',        'NONE'],
            ['RJ45 PATCH CABLE (1.5m)',                          'consumable', 'NONE',        '1.5 METERS'],
            ['RJ45 PATCH CABLE (2m)',                            'consumable', 'RANDOM',      '2 METERS'],
            ['RJ45 PATCH CABLE (3m)',                            'consumable', 'NONE',        '3 METERS'],
            ['RJ45 PATCH CABLE (5m)',                            'consumable', 'NONE',        '5 METERS'],
            ['RJ45 PATCH CABLE (10m)',                           'consumable', 'NONE',        '10 METERS'],
            ['RJ45 PATCH CABLE (20m)',                           'consumable', 'NONE',        '20 METERS'],
            ['RJ45 TO HDMI EXTENDER',                            'consumable', 'NONE',        'NONE'],
            ['CAT6 LAN CABLE (30m)',                             'consumable', 'NONE',        '30 METERS'],
            ['FLEX CABLE FOR EPSON L310 SERIES',                 'consumable', 'EPSON',       'L310 SERIES'],
            ['RJ11 CONNECTOR (CLEAR) 100PCS/PCK',               'consumable', 'NONE',        'NONE'],
            ['KEYSTONE JACK',                                    'consumable', 'N/A',         'N/A'],
            ['UPS BATTERY (ACCUPOWER)',                          'consumable', 'ACCUPOWER',   'N/A'],
            ['INDOOR UTP NETWORK CABLE CAT6 305M',              'consumable', 'COMLINK',     'N/A'],
            ['CABLE TIE (WHITE 2.5mmX100mm)',                    'consumable', 'TOLSEN',      'WHITE 2.5mmX100mm'],
            ['CABLE TIE (WHITE 3.6mmX200mm)',                    'consumable', 'N/A',         'WHITE 3.6mmX200mm'],
            ['CABLE TIE (WHITE 140mmX2.5mm)',                    'consumable', 'TOLSEN',      'WHITE 140mmX2.5mm'],
            ['CABLE TIE (WHITE 140mmX3.6mm TOLSEN)',             'consumable', 'TOLSEN',      'WHITE 140mmX3.6mm'],
            ['CABLE TIE (WHITE 140mmX3.6mm)',                    'consumable', 'N/A',         'WHITE 140mmX3.6mm'],
            ['CABLE TIE (WHITE 200mmX4.88mm)',                   'consumable', 'N/A',         'WHITE 200mmX4.88mm'],
            ['CABLE TIE (BLACK 250mmX4.8mm)',                    'consumable', 'N/A',         'BLACK 250mmX4.8mm'],
            ['CABLE TIE (WHITE 250mmX4.8mm)',                    'consumable', 'N/A',         'WHITE 250mmX4.8mm'],
            ['CABLE TIE (WHITE 300mmX3.6mm)',                    'consumable', 'N/A',         'WHITE 300mmX3.6mm'],
            ['CABLE TIE (WHITE 400mmX4.8mm)',                    'consumable', 'N/A',         'WHITE 400mmX4.8mm'],
            ['CABLE TIE (WHITE 300mmX4.8mm)',                    'consumable', 'N/A',         'WHITE 300mmX4.8mm'],
            ['CABLE TIE (BLACK 400mmX4.8mm)',                    'consumable', 'N/A',         'BLACK 400mmX4.8mm'],
            ['CABLE TIE (BLACK 300mmX4.8m)',                     'consumable', 'N/A',         'BLACK 300mmX4.8'],
            ['CABLE TIE (BLACK 300mmX3.6mm)',                    'consumable', 'N/A',         'BLACK 300mmX3.6mm'],
            ['CABLE TIE (BLACK 200mmX3.6mm)',                    'consumable', 'N/A',         'BLACK 200mmX3.6mm'],
            ['CABLE TIE (BLACK 200mmX4.8mm)',                    'consumable', 'N/A',         'BLACK 200mmX4.8mm'],
            ['CABLE TIE (BLACK 150mmX2.3mm)',                    'consumable', 'N/A',         'BLACK 150mmX2.3MM'],
            ['CABLE TIE (BLACK 140mmX2.5mm)',                    'consumable', 'N/A',         'BLACK 140mmX2.5mm'],
            ['CABLE TIE (BLACK 100mmX2.5mm)',                    'consumable', 'N/A',         'BLACK 100MMX2.5mm'],
            ['HDMI TRANSCEIVER EXTENDER',                        'consumable', 'N/A',         'N/A'],
            ['3 LAYER METAL DOCUMENT TRAY',                      'consumable', 'TM',          'DT-400'],
            ['MAINTENANCE KIT (MK-137)',                         'consumable', 'KYOCERA',     'MK-137'],
            ['MAINTENANCE KIT (MK-6119)',                        'consumable', 'KYOCERA',     'MK-6119'],
            ['USB MOUSE (LOGITECH)',                             'consumable', 'LOGITECH',    'B100'],
            ['USB KEYBOARD (LOGITECH)',                          'consumable', 'LOGITECH',    'K120'],
            ['USB KEYBOARD (XITRIX)',                            'consumable', 'XITRIX',      'XPN-KM165'],
            ['TONER KIT TK-479',                                'consumable', 'KYOCERA',     'TK-479'],
            ['TONER KIT TK-1147',                               'consumable', 'KYOCERA',     'TK-1147'],
            ['TONER KIT TK-1168',                               'consumable', 'KYOCERA',     'TK-1168'],
            ['TONER KIT TK-7120',                               'consumable', 'KYOCERA',     'TK-7120'],
            ['TONER KIT 12V7-Ah',                               'consumable', 'ACCU-CELL',   '12V7-Ah'],
            ['TONER KIT BLACK TK-1175',                         'consumable', 'KYOCERA',     'TK-1175'],
            ['TONER KIT BLACK TK-8113K',                        'consumable', 'KYOCERA',     'TK-8113K'],
            ['TONER KIT CYAN TK-8113C',                         'consumable', 'KYOCERA',     'TK-8113C'],
            ['TONER KIT MAGENTA TK-8113M',                      'consumable', 'KYOCERA',     'TK-8113M'],
            ['TONER KIT YELLOW TK-8113Y',                       'consumable', 'KYOCERA',     'TK-8113Y'],
            ['TONER KIT BLACK TK-5275K',                        'consumable', 'KYOCERA',     'TK-5275K'],
            ['TONER KIT CYAN TK-5275C',                         'consumable', 'KYOCERA',     'TK-5275C'],
            ['TONER KIT MAGENTA TK-5275M',                      'consumable', 'KYOCERA',     'TK-5275M'],
            ['TONER KIT YELLOW TK-5275Y',                       'consumable', 'KYOCERA',     'TK-5275Y'],
            ['TONER KIT BLACK TK-5234K',                        'consumable', 'KYOCERA',     'TK-5234K'],
            ['TONER KIT CYAN TK-5234C',                         'consumable', 'KYOCERA',     'TK-5234C'],
            ['TONER KIT MAGENTA TK-5234M',                      'consumable', 'KYOCERA',     'TK-5234M'],
            ['TONER KIT YELLOW TK-5234Y',                       'consumable', 'KYOCERA',     'TK-5234Y'],
            ['UPS BATTERY (ACCU-CELL 12V7-Ah)',                 'consumable', 'ACCU-CELL',   '12V7-Ah'],
            ['LAPTOP BAG',                                       'consumable', 'ACER',        'NONE'],
            ['CCTV JUNCTION BOX',                               'consumable', 'N/A',         'NONE'],
            ['WALL FACEPLATE WITH MOUNTING JUNCTION BOX',       'consumable', 'N/A',         'NONE'],
            ['HDMI CABLE 2.0V',                                 'consumable', 'HDTV',        '2.0V'],
            ['GENUINE INK BLACK (T6641)',                        'consumable', 'EPSON',       'T6641 BLACK'],
            ['GENUINE INK CYAN (T6642)',                         'consumable', 'EPSON',       'T6642 CYAN'],
            ['GENUINE INK YELLOW (T6644)',                       'consumable', 'EPSON',       'T6644 YELLOW'],
            ['GENUINE INK MAGENTA (T6643)',                      'consumable', 'EPSON',       'T6643 MAGENTA'],
            ['GENUINE INK (BROTHER BT5000C)',                    'consumable', 'BROTHER',     'BT5000C'],
            ['GENUINE INK (BROTHER BT5000M)',                    'consumable', 'BROTHER',     'BT5000M'],
            ['GENUINE INK (BROTHER BT5000Y)',                    'consumable', 'BROTHER',     'BT5000Y'],
            ['GENUINE INK (BROTHER BT6000K)',                    'consumable', 'BROTHER',     'BT6000K'],
            ['PIGMENT INK BLACK T7741',                         'consumable', 'EPSON',       'T7741 BLACK'],
            ['CYAN INK GT52',                                   'consumable', 'HP',          'GT52'],
            ['MAGENTA INK GT52',                                'consumable', 'HP',          'GT52'],
            ['YELLOW INK GT52',                                 'consumable', 'HP',          'GT52'],
            ['BLACK INK GT52',                                  'consumable', 'HP',          'GT52'],
            ['GENUINE RIBBON CARTRIDGE (60m)',                   'consumable', 'EPSON',       '60 METERS'],
            ['BLUE FILE HOLDER',                                'consumable', 'N/A',         'N/A'],
            ['TELEPHONE WIRE BLACK SHEATH',                     'consumable', 'N/A',         'WITH RJ11 6P4C MODULAR CONNECTORS'],
            ['CABLE THHN/THWN-2',                               'consumable', 'THHN/THWN-2', 'N/A'],
            ['CCTV JUNCTION BOX (TYPE 2)',                       'consumable', 'N/A',         'N/A'],
            ['WALL FACEPLATE SINGLE PORT',                      'consumable', 'N/A',         'N/A'],
            ['WALL FACEPLATE DUAL PORT',                        'consumable', 'N/A',         'N/A'],
            ['CCTV JUNCTION BOX (TYPE 3)',                       'consumable', 'N/A',         'N/A'],
            ['PABX EXPANSION CONTROLLER NEC',                    'device',     'NEC',         'SL1000'],
            ['3U CABLE MANAGEMENT ARM KIT',                     'consumable', 'N/A',         'N/A'],
            ['KIT RCKRL CMA SPR 2U',                            'consumable', 'N/A',         'N/A'],
            ['PATCH PANEL',                                     'consumable', '3M',          'N/A'],
            ['CABLE MANAGEMENT ARM',                            'consumable', 'N/A',         'N/A'],
            ['TV WALL MOUNT 26-55 HY101A',                      'consumable', 'N/A',         'HY101A'],
            ['KIT RCKL CMA SPR 2U',                             'consumable', 'N/A',         'N/A'],
            ['TV WALL MOUNT 14-42 (V-STAR)',                     'consumable', 'V-STAR',      'CP302S'],
            ['TV WALL MOUNT 14-42',                             'consumable', 'N/A',         'N/A'],
            ['BLACK INK PG-40',                                 'consumable', 'CANON',       'PG-40'],
            ['BLACK INK PG-88',                                 'consumable', 'CANON',       'PG-88'],
            ['BLACK INK PG-745XL',                              'consumable', 'CANON',       'PG-745XL'],
            ['KIT RAILS SLIDING READYRAILS II 3U C2',           'consumable', 'N/A',         'N/A'],
            ['RACK',                                            'consumable', 'KING SLIDE',  'N/A'],
        ];

        foreach ($items as [$name, $type, $brand, $model]) {
            $catId = $type === 'device' ? $deviceCat->id : $consumableCat->id;
            Item::create([
                'name'        => $name,
                'item_type'   => $type,
                'brand'       => $brand,
                'model'       => $model,
                'category_id' => $catId,
                'unit'        => 'pcs',
                'stock_used'  => 0,
            ]);
        }

        $this->command->info('✓ Replaced all items — ' . count($items) . ' items created.');
    }
}

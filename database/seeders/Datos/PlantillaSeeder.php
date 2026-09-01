<?php

namespace Database\Seeders\Datos;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\IdiomaPlantilla;
use Illuminate\Database\Seeder;

class PlantillaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->datos();
    }

    public function datos()
    {
        $idiomas = [
            ["nombre" => "Afrikaans", "codigo" => "af"],
            ["nombre" => "Albanian", "codigo" => "sq"],
            ["nombre" => "Arabic", "codigo" => "ar"],
            ["nombre" => "Arabic (EGY)", "codigo" => "ar_EG"],
            ["nombre" => "Arabic (UAE)", "codigo" => "ar_AE"],
            ["nombre" => "Arabic (LBN)", "codigo" => "ar_LB"],
            ["nombre" => "Arabic (MAR)", "codigo" => "ar_MA"],
            ["nombre" => "Arabic (QAT)", "codigo" => "ar_QA"],
            ["nombre" => "Azerbaijani", "codigo" => "az"],
            ["nombre" => "Belarusian", "codigo" => "be_BY"],
            ["nombre" => "Bengali", "codigo" => "bn"],
            ["nombre" => "Bengali (IND)", "codigo" => "bn_IN"],
            ["nombre" => "Bulgarian", "codigo" => "bg"],
            ["nombre" => "Catalan", "codigo" => "ca"],
            ["nombre" => "Chinese (CHN)", "codigo" => "zh_CN"],
            ["nombre" => "Chinese (HKG)", "codigo" => "zh_HK"],
            ["nombre" => "Chinese (TAI)", "codigo" => "zh_TW"],
            ["nombre" => "Croatian", "codigo" => "hr"],
            ["nombre" => "Czech", "codigo" => "cs"],
            ["nombre" => "Danish", "codigo" => "da"],
            ["nombre" => "Dari", "codigo" => "prs_AF"],
            ["nombre" => "Dutch", "codigo" => "nl"],
            ["nombre" => "Dutch (BEL)", "codigo" => "nl_BE"],
            ["nombre" => "English", "codigo" => "en"],
            ["nombre" => "English (UK)", "codigo" => "en_GB"],
            ["nombre" => "English (US)", "codigo" => "en_US"],
            ["nombre" => "English (UAE)", "codigo" => "en_AE"],
            ["nombre" => "English (AUS)", "codigo" => "en_AU"],
            ["nombre" => "English (CAN)", "codigo" => "en_CA"],
            ["nombre" => "English (GHA)", "codigo" => "en_GH"],
            ["nombre" => "English (IRL)", "codigo" => "en_IE"],
            ["nombre" => "English (IND)", "codigo" => "en_IN"],
            ["nombre" => "English (JAM)", "codigo" => "en_JM"],
            ["nombre" => "English (MYS)", "codigo" => "en_MY"],
            ["nombre" => "English (NZL)", "codigo" => "en_NZ"],
            ["nombre" => "English (QAT)", "codigo" => "en_QA"],
            ["nombre" => "English (SGP)", "codigo" => "en_SG"],
            ["nombre" => "English (UGA)", "codigo" => "en_UG"],
            ["nombre" => "English (ZAF)", "codigo" => "en_ZA"],
            ["nombre" => "Estonian", "codigo" => "et"],
            ["nombre" => "Filipino", "codigo" => "fil"],
            ["nombre" => "Finnish", "codigo" => "fi"],
            ["nombre" => "French", "codigo" => "fr"],
            ["nombre" => "French (BEL)", "codigo" => "fr_BE"],
            ["nombre" => "French (CAN)", "codigo" => "fr_CA"],
            ["nombre" => "French (CHE)", "codigo" => "fr_CH"],
            ["nombre" => "French (CIV)", "codigo" => "fr_CI"],
            ["nombre" => "French (MAR)", "codigo" => "fr_MA"],
            ["nombre" => "Georgian", "codigo" => "ka"],
            ["nombre" => "German", "codigo" => "de"],
            ["nombre" => "German (AUT)", "codigo" => "de_AT"],
            ["nombre" => "German (CHE)", "codigo" => "de_CH"],
            ["nombre" => "Greek", "codigo" => "el"],
            ["nombre" => "Gujarati", "codigo" => "gu"],
            ["nombre" => "Hausa", "codigo" => "ha"],
            ["nombre" => "Hebrew", "codigo" => "he"],
            ["nombre" => "Hindi", "codigo" => "hi"],
            ["nombre" => "Hungarian", "codigo" => "hu"],
            ["nombre" => "Indonesian", "codigo" => "id"],
            ["nombre" => "Irish", "codigo" => "ga"],
            ["nombre" => "Italian", "codigo" => "it"],
            ["nombre" => "Japanese", "codigo" => "ja"],
            ["nombre" => "Kannada", "codigo" => "kn"],
            ["nombre" => "Kazakh", "codigo" => "kk"],
            ["nombre" => "Kinyarwanda", "codigo" => "rw_RW"],
            ["nombre" => "Korean", "codigo" => "ko"],
            ["nombre" => "Kyrgyz", "codigo" => "ky_KG"],
            ["nombre" => "Lao", "codigo" => "lo"],
            ["nombre" => "Latvian", "codigo" => "lv"],
            ["nombre" => "Lithuanian", "codigo" => "lt"],
            ["nombre" => "Macedonian", "codigo" => "mk"],
            ["nombre" => "Malay", "codigo" => "ms"],
            ["nombre" => "Malayalam", "codigo" => "ml"],
            ["nombre" => "Marathi", "codigo" => "mr"],
            ["nombre" => "Norwegian", "codigo" => "nb"],
            ["nombre" => "Pashto", "codigo" => "ps_AF"],
            ["nombre" => "Persian", "codigo" => "fa"],
            ["nombre" => "Polish", "codigo" => "pl"],
            ["nombre" => "Portuguese (BR)", "codigo" => "pt_BR"],
            ["nombre" => "Portuguese (POR)", "codigo" => "pt_PT"],
            ["nombre" => "Punjabi", "codigo" => "pa"],
            ["nombre" => "Romanian", "codigo" => "ro"],
            ["nombre" => "Russian", "codigo" => "ru"],
            ["nombre" => "Serbian", "codigo" => "sr"],
            ["nombre" => "Sinhala", "codigo" => "si_LK"],
            ["nombre" => "Slovak", "codigo" => "sk"],
            ["nombre" => "Slovenian", "codigo" => "sl"],
            ["nombre" => "Spanish", "codigo" => "es"],
            ["nombre" => "Spanish (ARG)", "codigo" => "es_AR"],
            ["nombre" => "Spanish (CHL)", "codigo" => "es_CL"],
            ["nombre" => "Spanish (COL)", "codigo" => "es_CO"],
            ["nombre" => "Spanish (CRI)", "codigo" => "es_CR"],
            ["nombre" => "Spanish (DOM)", "codigo" => "es_DO"],
            ["nombre" => "Spanish (ECU)", "codigo" => "es_EC"],
            ["nombre" => "Spanish (HND)", "codigo" => "es_HN"],
            ["nombre" => "Spanish (MEX)", "codigo" => "es_MX"],
            ["nombre" => "Spanish (PAN)", "codigo" => "es_PA"],
            ["nombre" => "Spanish (PER)", "codigo" => "es_PE"],
            ["nombre" => "Spanish (SPA)", "codigo" => "es_ES"],
            ["nombre" => "Spanish (URY)", "codigo" => "es_UY"],
            ["nombre" => "Swahili", "codigo" => "sw"],
            ["nombre" => "Swedish", "codigo" => "sv"],
            ["nombre" => "Tamil", "codigo" => "ta"],
            ["nombre" => "Telugu", "codigo" => "te"],
            ["nombre" => "Thai", "codigo" => "th"],
            ["nombre" => "Turkish", "codigo" => "tr"],
            ["nombre" => "Ukrainian", "codigo" => "uk"],
            ["nombre" => "Urdu", "codigo" => "ur"],
            ["nombre" => "Uzbek", "codigo" => "uz"],
            ["nombre" => "Vietnamese", "codigo" => "vi"],
            ["nombre" => "Zulu", "codigo" => "zu"],
        ];

        foreach ($idiomas as $idioma) {
            IdiomaPlantilla::updateOrCreate([
                'codigo' => $idioma['codigo']
            ], $idioma);
        }
    }
}

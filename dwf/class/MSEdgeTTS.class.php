<?php

/**
 * Cette classe permet d'utiliser le service TTS de Micrisoft Edge
 * passe par le miroir tts.webextools.com
 * Attention, conformément aux CGU de Microsoft, l'utilisation de cette classe n'est pas autorisé dans le cadre d'un usage commercial
 * 
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class MSEdgeTTS {
    // Constantes pour les voix de MS Edge TTS

    /** Adri - Afrikaans (South Africa) - Female */
    const VOICE_AF_ZA_ADRI = 'af-ZA-AdriNeural';

    /** Willem - Afrikaans (South Africa) - Male */
    const VOICE_AF_ZA_WILLEM = 'af-ZA-WillemNeural';

    /** Anila - Albanian (Albania) - Female */
    const VOICE_SQ_AL_ANILA = 'sq-AL-AnilaNeural';

    /** Ilir - Albanian (Albania) - Male */
    const VOICE_SQ_AL_ILIR = 'sq-AL-IlirNeural';

    /** Ameha - Amharic (Ethiopia) - Male */
    const VOICE_AM_ET_AMEHA = 'am-ET-AmehaNeural';

    /** Mekdes - Amharic (Ethiopia) - Female */
    const VOICE_AM_ET_MEKDES = 'am-ET-MekdesNeural';

    /** Amina - Arabic (Algeria) - Female */
    const VOICE_AR_DZ_AMINA = 'ar-DZ-AminaNeural';

    /** Ismael - Arabic (Algeria) - Male */
    const VOICE_AR_DZ_ISMAEL = 'ar-DZ-IsmaelNeural';

    /** Ali - Arabic (Bahrain) - Male */
    const VOICE_AR_BH_ALI = 'ar-BH-AliNeural';

    /** Laila - Arabic (Bahrain) - Female */
    const VOICE_AR_BH_LAILA = 'ar-BH-LailaNeural';

    /** Salma - Arabic (Egypt) - Female */
    const VOICE_AR_EG_SALMA = 'ar-EG-SalmaNeural';

    /** Shakir - Arabic (Egypt) - Male */
    const VOICE_AR_EG_SHAKIR = 'ar-EG-ShakirNeural';

    /** Bassel - Arabic (Iraq) - Male */
    const VOICE_AR_IQ_BASSEL = 'ar-IQ-BasselNeural';

    /** Rana - Arabic (Iraq) - Female */
    const VOICE_AR_IQ_RANA = 'ar-IQ-RanaNeural';

    /** Sana - Arabic (Jordan) - Female */
    const VOICE_AR_JO_SANA = 'ar-JO-SanaNeural';

    /** Taim - Arabic (Jordan) - Male */
    const VOICE_AR_JO_TAIM = 'ar-JO-TaimNeural';

    /** Fahed - Arabic (Kuwait) - Male */
    const VOICE_AR_KW_FAHED = 'ar-KW-FahedNeural';

    /** Noura - Arabic (Kuwait) - Female */
    const VOICE_AR_KW_NOURA = 'ar-KW-NouraNeural';

    /** Layla - Arabic (Lebanon) - Female */
    const VOICE_AR_LB_LAYLA = 'ar-LB-LaylaNeural';

    /** Rami - Arabic (Lebanon) - Male */
    const VOICE_AR_LB_RAMI = 'ar-LB-RamiNeural';

    /** Iman - Arabic (Libya) - Female */
    const VOICE_AR_LY_IMAN = 'ar-LY-ImanNeural';

    /** Omar - Arabic (Libya) - Male */
    const VOICE_AR_LY_OMAR = 'ar-LY-OmarNeural';

    /** Jamal - Arabic (Morocco) - Male */
    const VOICE_AR_MA_JAMAL = 'ar-MA-JamalNeural';

    /** Mouna - Arabic (Morocco) - Female */
    const VOICE_AR_MA_MOUNA = 'ar-MA-MounaNeural';

    /** Abdullah - Arabic (Oman) - Male */
    const VOICE_AR_OM_ABDULLAH = 'ar-OM-AbdullahNeural';

    /** Aysha - Arabic (Oman) - Female */
    const VOICE_AR_OM_AYSHA = 'ar-OM-AyshaNeural';

    /** Amal - Arabic (Qatar) - Female */
    const VOICE_AR_QA_AMAL = 'ar-QA-AmalNeural';

    /** Moaz - Arabic (Qatar) - Male */
    const VOICE_AR_QA_MOAZ = 'ar-QA-MoazNeural';

    /** Hamed - Arabic (Saudi Arabia) - Male */
    const VOICE_AR_SA_HAMED = 'ar-SA-HamedNeural';

    /** Zariyah - Arabic (Saudi Arabia) - Female */
    const VOICE_AR_SA_ZARIYAH = 'ar-SA-ZariyahNeural';

    /** Amany - Arabic (Syria) - Female */
    const VOICE_AR_SY_AMANY = 'ar-SY-AmanyNeural';

    /** Laith - Arabic (Syria) - Male */
    const VOICE_AR_SY_LAITH = 'ar-SY-LaithNeural';

    /** Hedi - Arabic (Tunisia) - Male */
    const VOICE_AR_TN_HEDI = 'ar-TN-HediNeural';

    /** Reem - Arabic (Tunisia) - Female */
    const VOICE_AR_TN_REEM = 'ar-TN-ReemNeural';

    /** Fatima - Arabic (United Arab Emirates) - Female */
    const VOICE_AR_AE_FATIMA = 'ar-AE-FatimaNeural';

    /** Hamdan - Arabic (United Arab Emirates) - Male */
    const VOICE_AR_AE_HAMDAN = 'ar-AE-HamdanNeural';

    /** Maryam - Arabic (Yemen) - Female */
    const VOICE_AR_YE_MARYAM = 'ar-YE-MaryamNeural';

    /** Saleh - Arabic (Yemen) - Male */
    const VOICE_AR_YE_SALEH = 'ar-YE-SalehNeural';

    /** Babek - Azerbaijani (Azerbaijan) - Male */
    const VOICE_AZ_AZ_BABEK = 'az-AZ-BabekNeural';

    /** Banu - Azerbaijani (Azerbaijan) - Female */
    const VOICE_AZ_AZ_BANU = 'az-AZ-BanuNeural';

    /** Nabanita - Bangla (Bangladesh) - Female */
    const VOICE_BN_BD_NABANITA = 'bn-BD-NabanitaNeural';

    /** Pradeep - Bangla (Bangladesh) - Male */
    const VOICE_BN_BD_PRADEEP = 'bn-BD-PradeepNeural';

    /** Bashkar - Bengali (India) - Male */
    const VOICE_BN_IN_BASHKAR = 'bn-IN-BashkarNeural';

    /** Tanishaa - Bengali (India) - Female */
    const VOICE_BN_IN_TANISHAA = 'bn-IN-TanishaaNeural';

    /** Goran - Bosnian (Bosnia and Herzegovina) - Male */
    const VOICE_BS_BA_GORAN = 'bs-BA-GoranNeural';

    /** Vesna - Bosnian (Bosnia and Herzegovina) - Female */
    const VOICE_BS_BA_VESNA = 'bs-BA-VesnaNeural';

    /** Borislav - Bulgarian (Bulgaria) - Male */
    const VOICE_BG_BG_BORISLAV = 'bg-BG-BorislavNeural';

    /** Kalina - Bulgarian (Bulgaria) - Female */
    const VOICE_BG_BG_KALINA = 'bg-BG-KalinaNeural';

    /** Nilar - Burmese (Myanmar) - Female */
    const VOICE_MY_MM_NILAR = 'my-MM-NilarNeural';

    /** Thiha - Burmese (Myanmar) - Male */
    const VOICE_MY_MM_THIHA = 'my-MM-ThihaNeural';

    /** Alba - Catalan (Spain) - Female */
    const VOICE_CA_ES_ALBA = 'ca-ES-AlbaNeural';

    /** Enric - Catalan (Spain) - Male */
    const VOICE_CA_ES_ENRIC = 'ca-ES-EnricNeural';

    /** Joana - Catalan (Spain) - Female */
    const VOICE_CA_ES_JOANA = 'ca-ES-JoanaNeural';

    /** HiuGaai - Chinese (Hong Kong) - Female */
    const VOICE_ZH_HK_HIUGAAI = 'zh-HK-HiuGaaiNeural';

    /** HiuMaan - Chinese (Hong Kong) - Female */
    const VOICE_ZH_HK_HIUMAAN = 'zh-HK-HiuMaanNeural';

    /** WanLung - Chinese (Hong Kong) - Male */
    const VOICE_ZH_HK_WANLUNG = 'zh-HK-WanLungNeural';

    /** Xiaoxiao - Chinese (Mainland China) - Female */
    const VOICE_ZH_CN_XIAOXIAO = 'zh-CN-XiaoxiaoNeural';

    /** Xiaoyi - Chinese (Mainland China) - Female */
    const VOICE_ZH_CN_XIAOYI = 'zh-CN-XiaoyiNeural';

    /** Yunjian - Chinese (Mainland China) - Male */
    const VOICE_ZH_CN_YUNJIAN = 'zh-CN-YunjianNeural';

    /** Yunxi - Chinese (Mainland China) - Male */
    const VOICE_ZH_CN_YUNXI = 'zh-CN-YunxiNeural';

    /** Yunxia - Chinese (Mainland China) - Male */
    const VOICE_ZH_CN_YUNXIA = 'zh-CN-YunxiaNeural';

    /** Yunyang - Chinese (Mainland China) - Male */
    const VOICE_ZH_CN_YUNYANG = 'zh-CN-YunyangNeural';

    /** Xiaobei - Chinese (Liaoning) - Female */
    const VOICE_ZH_CN_LIAONING_XIAOBEI = 'zh-CN-liaoning-XiaobeiNeural';

    /** HsiaoChen - Chinese (Taiwan) - Female */
    const VOICE_ZH_TW_HSIAOCHEN = 'zh-TW-HsiaoChenNeural';

    /** HsiaoYu - Chinese (Taiwan) - Female */
    const VOICE_ZH_TW_HSIAOYU = 'zh-TW-HsiaoYuNeural';

    /** YunJhe - Chinese (Taiwan) - Male */
    const VOICE_ZH_TW_YUNJHE = 'zh-TW-YunJheNeural';

    /** Xiaoni - Chinese (Shaanxi) - Female */
    const VOICE_ZH_CN_SHAANXI_XIAONI = 'zh-CN-shaanxi-XiaoniNeural';

    /** Gabrijel - Croatian (Croatia) - Male */
    const VOICE_HR_HR_GABRIJEL = 'hr-HR-GabrijelNeural';

    /** Srecko - Croatian (Croatia) - Male */
    const VOICE_HR_HR_SRECKO = 'hr-HR-SreckoNeural';

    /** Antonin - Czech (Czech Republic) - Male */
    const VOICE_CS_CZ_ANTONIN = 'cs-CZ-AntoninNeural';

    /** Vlasta - Czech (Czech Republic) - Female */
    const VOICE_CS_CZ_VLASTA = 'cs-CZ-VlastaNeural';

    /** Christel - Danish (Denmark) - Female */
    const VOICE_DA_DK_CHRISTEL = 'da-DK-ChristelNeural';

    /** Jeppe - Danish (Denmark) - Male */
    const VOICE_DA_DK_JEPPE = 'da-DK-JeppeNeural';

    /** Arnaud - Dutch (Belgium) - Male */
    const VOICE_NL_BE_ARNAUD = 'nl-BE-ArnaudNeural';

    /** Dena - Dutch (Belgium) - Female */
    const VOICE_NL_BE_DENA = 'nl-BE-DenaNeural';

    /** Colette - Dutch (Netherlands) - Female */
    const VOICE_NL_NL_COLETTE = 'nl-NL-ColetteNeural';

    /** Fenna - Dutch (Netherlands) - Female */
    const VOICE_NL_NL_FENNA = 'nl-NL-FennaNeural';

    /** Maarten - Dutch (Netherlands) - Male */
    const VOICE_NL_NL_MAARTEN = 'nl-NL-MaartenNeural';

    /** Annette - English (Australia) - Female */
    const VOICE_EN_AU_ANNETTE = 'en-AU-AnnetteNeural';

    /** Carly - English (Australia) - Female */
    const VOICE_EN_AU_CARLY = 'en-AU-CarlyNeural';

    /** Darren - English (Australia) - Male */
    const VOICE_EN_AU_DARREN = 'en-AU-DarrenNeural';

    /** Duncan - English (Australia) - Male */
    const VOICE_EN_AU_DUNCAN = 'en-AU-DuncanNeural';

    /** Elsie - English (Australia) - Female */
    const VOICE_EN_AU_ELSIE = 'en-AU-ElsieNeural';

    /** Freya - English (Australia) - Female */
    const VOICE_EN_AU_FREYA = 'en-AU-FreyaNeural';

    /** Joanne - English (Australia) - Female */
    const VOICE_EN_AU_JOANNE = 'en-AU-JoanneNeural';

    /** Ken - English (Australia) - Male */
    const VOICE_EN_AU_KEN = 'en-AU-KenNeural';

    /** Kim - English (Australia) - Female */
    const VOICE_EN_AU_KIM = 'en-AU-KimNeural';

    /** Natasha - English (Australia) - Female */
    const VOICE_EN_AU_NATASHA = 'en-AU-NatashaNeural';

    /** Neil - English (Australia) - Male */
    const VOICE_EN_AU_NEIL = 'en-AU-NeilNeural';

    /** Tim - English (Australia) - Male */
    const VOICE_EN_AU_TIM = 'en-AU-TimNeural';

    /** Tina - English (Australia) - Female */
    const VOICE_EN_AU_TINA = 'en-AU-TinaNeural';

    /** William - English (Australia) - Male */
    const VOICE_EN_AU_WILLIAM = 'en-AU-WilliamNeural';

    /** Clara - English (Canada) - Female */
    const VOICE_EN_CA_CLARA = 'en-CA-ClaraNeural';

    /** Liam - English (Canada) - Male */
    const VOICE_EN_CA_LIAM = 'en-CA-LiamNeural';

    /** Sam - English (Hong Kong) - Male */
    const VOICE_EN_HK_SAM = 'en-HK-SamNeural';

    /** Yan - English (Hong Kong) - Female */
    const VOICE_EN_HK_YAN = 'en-HK-YanNeural';

    /** Aarav - English (India) - Male */
    const VOICE_EN_IN_AARAV = 'en-IN-AaravNeural';

    /** Aashi - English (India) - Female */
    const VOICE_EN_IN_AASHI = 'en-IN-AashiNeural';

    /** Neerja - English (India) - Female */
    const VOICE_EN_IN_NEERJA = 'en-IN-NeerjaNeural';

    /** Prabhat - English (India) - Male */
    const VOICE_EN_IN_PRABHAT = 'en-IN-PrabhatNeural';

    /** Connor - English (Ireland) - Male */
    const VOICE_EN_IE_CONNOR = 'en-IE-ConnorNeural';

    /** Emily - English (Ireland) - Female */
    const VOICE_EN_IE_EMILY = 'en-IE-EmilyNeural';

    /** Asilia - English (Kenya) - Female */
    const VOICE_EN_KE_ASILIA = 'en-KE-AsiliaNeural';

    /** Chilemba - English (Kenya) - Male */
    const VOICE_EN_KE_CHILEMBA = 'en-KE-ChilembaNeural';

    /** Mitchell - English (New Zealand) - Male */
    const VOICE_EN_NZ_MITCHELL = 'en-NZ-MitchellNeural';

    /** Molly - English (New Zealand) - Female */
    const VOICE_EN_NZ_MOLLY = 'en-NZ-MollyNeural';

    /** Abeo - English (Nigeria) - Male */
    const VOICE_EN_NG_ABEO = 'en-NG-AbeoNeural';

    /** Ezinne - English (Nigeria) - Female */
    const VOICE_EN_NG_EZINNE = 'en-NG-EzinneNeural';

    /** James - English (Philippines) - Male */
    const VOICE_EN_PH_JAMES = 'en-PH-JamesNeural';

    /** Rosa - English (Philippines) - Female */
    const VOICE_EN_PH_ROSA = 'en-PH-RosaNeural';

    /** Luna - English (Singapore) - Female */
    const VOICE_EN_SG_LUNA = 'en-SG-LunaNeural';

    /** Wayne - English (Singapore) - Male */
    const VOICE_EN_SG_WAYNE = 'en-SG-WayneNeural';

    /** Leah - English (South Africa) - Female */
    const VOICE_EN_ZA_LEAH = 'en-ZA-LeahNeural';

    /** Luke - English (South Africa) - Male */
    const VOICE_EN_ZA_LUKE = 'en-ZA-LukeNeural';

    /** Elimu - English (Tanzania) - Male */
    const VOICE_EN_TZ_ELIMU = 'en-TZ-ElimuNeural';

    /** Imani - English (Tanzania) - Female */
    const VOICE_EN_TZ_IMANI = 'en-TZ-ImaniNeural';

    /** Abbi - English (United Kingdom) - Female */
    const VOICE_EN_GB_ABBI = 'en-GB-AbbiNeural';

    /** Alfie - English (United Kingdom) - Male */
    const VOICE_EN_GB_ALFIE = 'en-GB-AlfieNeural';

    /** Bella - English (United Kingdom) - Female */
    const VOICE_EN_GB_BELLA = 'en-GB-BellaNeural';

    /** Elliot - English (United Kingdom) - Male */
    const VOICE_EN_GB_ELLIOT = 'en-GB-ElliotNeural';

    /** Ethan - English (United Kingdom) - Male */
    const VOICE_EN_GB_ETHAN = 'en-GB-EthanNeural';

    /** Hollie - English (United Kingdom) - Female */
    const VOICE_EN_GB_HOLLIE = 'en-GB-HollieNeural';

    /** Libby - English (United Kingdom) - Female */
    const VOICE_EN_GB_LIBBY = 'en-GB-LibbyNeural';

    /** Maisie - English (United Kingdom) - Female */
    const VOICE_EN_GB_MAISIE = 'en-GB-MaisieNeural';

    /** Noah - English (United Kingdom) - Male */
    const VOICE_EN_GB_NOAH = 'en-GB-NoahNeural';

    /** Oliver - English (United Kingdom) - Male */
    const VOICE_EN_GB_OLIVER = 'en-GB-OliverNeural';

    /** Olivia - English (United Kingdom) - Female */
    const VOICE_EN_GB_OLIVIA = 'en-GB-OliviaNeural';

    /** Ryan - English (United Kingdom) - Male */
    const VOICE_EN_GB_RYAN = 'en-GB-RyanNeural';

    /** Sonia - English (United Kingdom) - Female */
    const VOICE_EN_GB_SONIA = 'en-GB-SoniaNeural';

    /** Thomas - English (United Kingdom) - Male */
    const VOICE_EN_GB_THOMAS = 'en-GB-ThomasNeural';

    /** Ana - English (United States) - Female */
    const VOICE_EN_US_ANA = 'en-US-AnaNeural';

    /** Aria - English (United States) - Female */
    const VOICE_EN_US_ARIA = 'en-US-AriaNeural';

    /** Ashley - English (United States) - Female */
    const VOICE_EN_US_ASHLEY = 'en-US-AshleyNeural';

    /** Ava - English (United States) - Female */
    const VOICE_EN_US_AVA = 'en-US-AvaNeural';

    /** Andrew - English (United States) - Male */
    const VOICE_EN_US_ANDREW = 'en-US-AndrewNeural';

    /** Brandon - English (United States) - Male */
    const VOICE_EN_US_BRANDON = 'en-US-BrandonNeural';

    /** Christopher - English (United States) - Male */
    const VOICE_EN_US_CHRISTOPHER = 'en-US-ChristopherNeural';

    /** Cora - English (United States) - Female */
    const VOICE_EN_US_CORA = 'en-US-CoraNeural';

    /** Davis - English (United States) - Male */
    const VOICE_EN_US_DAVIS = 'en-US-DavisNeural';

    /** Elizabeth - English (United States) - Female */
    const VOICE_EN_US_ELIZABETH = 'en-US-ElizabethNeural';

    /** Emma - English (United States) - Female */
    const VOICE_EN_US_EMMA = 'en-US-EmmaNeural';

    /** Eric - English (United States) - Male */
    const VOICE_EN_US_ERIC = 'en-US-EricNeural';

    /** Guy - English (United States) - Male */
    const VOICE_EN_US_GUY = 'en-US-GuyNeural';

    /** Jacob - English (United States) - Male */
    const VOICE_EN_US_JACOB = 'en-US-JacobNeural';

    /** Jane - English (United States) - Female */
    const VOICE_EN_US_JANE = 'en-US-JaneNeural';

    /** Jason - English (United States) - Male */
    const VOICE_EN_US_JASON = 'en-US-JasonNeural';

    /** Jenny - English (United States) - Female */
    const VOICE_EN_US_JENNY = 'en-US-JennyNeural';

    /** Michelle - English (United States) - Female */
    const VOICE_EN_US_MICHELLE = 'en-US-MichelleNeural';

    /** Monica - English (United States) - Female */
    const VOICE_EN_US_MONICA = 'en-US-MonicaNeural';

    /** Nancy - English (United States) - Female */
    const VOICE_EN_US_NANCY = 'en-US-NancyNeural';

    /** Roger - English (United States) - Male */
    const VOICE_EN_US_ROGER = 'en-US-RogerNeural';

    /** Sara - English (United States) - Female */
    const VOICE_EN_US_SARA = 'en-US-SaraNeural';

    /** Steffan - English (United States) - Male */
    const VOICE_EN_US_STEFFAN = 'en-US-SteffanNeural';

    /** Tony - English (United States) - Male */
    const VOICE_EN_US_TONY = 'en-US-TonyNeural';

    /** Anu - Estonian (Estonia) - Female */
    const VOICE_ET_EE_ANU = 'et-EE-AnuNeural';

    /** Kert - Estonian (Estonia) - Male */
    const VOICE_ET_EE_KERT = 'et-EE-KertNeural';

    /** Angelo - Filipino (Philippines) - Male */
    const VOICE_FIL_PH_ANGELO = 'fil-PH-AngeloNeural';

    /** Blessica - Filipino (Philippines) - Female */
    const VOICE_FIL_PH_BLESSICA = 'fil-PH-BlessicaNeural';

    /** Harri - Finnish (Finland) - Male */
    const VOICE_FI_FI_HARRI = 'fi-FI-HarriNeural';

    /** Noora - Finnish (Finland) - Female */
    const VOICE_FI_FI_NOORA = 'fi-FI-NooraNeural';

    /** Charline - French (Belgium) - Female */
    const VOICE_FR_BE_CHARLINE = 'fr-BE-CharlineNeural';

    /** Gerard - French (Belgium) - Male */
    const VOICE_FR_BE_GERARD = 'fr-BE-GerardNeural';

    /** Antoine - French (Canada) - Male */
    const VOICE_FR_CA_ANTOINE = 'fr-CA-AntoineNeural';

    /** Jean - French (Canada) - Male */
    const VOICE_FR_CA_JEAN = 'fr-CA-JeanNeural';

    /** Sylvie - French (Canada) - Female */
    const VOICE_FR_CA_SYLVIE = 'fr-CA-SylvieNeural';

    /** Alain - French (France) - Male */
    const VOICE_FR_FR_ALAIN = 'fr-FR-AlainNeural';

    /** Brigitte - French (France) - Female */
    const VOICE_FR_FR_BRIGITTE = 'fr-FR-BrigitteNeural';

    /** Celeste - French (France) - Female */
    const VOICE_FR_FR_CELESTE = 'fr-FR-CelesteNeural';

    /** Claude - French (France) - Male */
    const VOICE_FR_FR_CLAUDE = 'fr-FR-ClaudeNeural';

    /** Coralie - French (France) - Female */
    const VOICE_FR_FR_CORALIE = 'fr-FR-CoralieNeural';

    /** Denise - French (France) - Female */
    const VOICE_FR_FR_DENISE = 'fr-FR-DeniseNeural';

    /** Eloise - French (France) - Female */
    const VOICE_FR_FR_ELOISE = 'fr-FR-EloiseNeural';

    /** Henri - French (France) - Male */
    const VOICE_FR_FR_HENRI = 'fr-FR-HenriNeural';

    /** Jacqueline - French (France) - Female */
    const VOICE_FR_FR_JACQUELINE = 'fr-FR-JacquelineNeural';

    /** Jerome - French (France) - Male */
    const VOICE_FR_FR_JEROME = 'fr-FR-JeromeNeural';

    /** Josephine - French (France) - Female */
    const VOICE_FR_FR_JOSEPHINE = 'fr-FR-JosephineNeural';

    /** Maurice - French (France) - Male */
    const VOICE_FR_FR_MAURICE = 'fr-FR-MauriceNeural';

    /** Yves - French (France) - Male */
    const VOICE_FR_FR_YVES = 'fr-FR-YvesNeural';

    /** Yvette - French (France) - Female */
    const VOICE_FR_FR_YVETTE = 'fr-FR-YvetteNeural';

    /** Ariane - French (Switzerland) - Female */
    const VOICE_FR_CH_ARIANE = 'fr-CH-ArianeNeural';

    /** Fabrice - French (Switzerland) - Male */
    const VOICE_FR_CH_FABRICE = 'fr-CH-FabriceNeural';

    /** Roi - Galician (Spain) - Male */
    const VOICE_GL_ES_ROI = 'gl-ES-RoiNeural';

    /** Sabela - Galician (Spain) - Female */
    const VOICE_GL_ES_SABELA = 'gl-ES-SabelaNeural';

    /** Eka - Georgian (Georgia) - Female */
    const VOICE_KA_GE_EKA = 'ka-GE-EkaNeural';

    /** Giorgi - Georgian (Georgia) - Male */
    const VOICE_KA_GE_GIORGI = 'ka-GE-GiorgiNeural';

    /** Ingrid - German (Austria) - Female */
    const VOICE_DE_AT_INGRID = 'de-AT-IngridNeural';

    /** Jonas - German (Austria) - Male */
    const VOICE_DE_AT_JONAS = 'de-AT-JonasNeural';

    /** Amala - German (Germany) - Female */
    const VOICE_DE_DE_AMALA = 'de-DE-AmalaNeural';

    /** Bernd - German (Germany) - Male */
    const VOICE_DE_DE_BERND = 'de-DE-BerndNeural';

    /** Christoph - German (Germany) - Male */
    const VOICE_DE_DE_CHRISTOPH = 'de-DE-ChristophNeural';

    /** Conrad - German (Germany) - Male */
    const VOICE_DE_DE_CONRAD = 'de-DE-ConradNeural';

    /** Elke - German (Germany) - Female */
    const VOICE_DE_DE_ELKE = 'de-DE-ElkeNeural';

    /** Gisela - German (Germany) - Female */
    const VOICE_DE_DE_GISELA = 'de-DE-GiselaNeural';

    /** Kasper - German (Germany) - Male */
    const VOICE_DE_DE_KASPER = 'de-DE-KasperNeural';

    /** Katja - German (Germany) - Female */
    const VOICE_DE_DE_KATJA = 'de-DE-KatjaNeural';

    /** Killian - German (Germany) - Male */
    const VOICE_DE_DE_KILLIAN = 'de-DE-KillianNeural';

    /** Klarissa - German (Germany) - Female */
    const VOICE_DE_DE_KLARISSA = 'de-DE-KlarissaNeural';

    /** Klaus - German (Germany) - Male */
    const VOICE_DE_DE_KLAUS = 'de-DE-KlausNeural';

    /** Louisa - German (Germany) - Female */
    const VOICE_DE_DE_LOUISA = 'de-DE-LouisaNeural';

    /** Maja - German (Germany) - Female */
    const VOICE_DE_DE_MAJA = 'de-DE-MajaNeural';

    /** Ralf - German (Germany) - Male */
    const VOICE_DE_DE_RALF = 'de-DE-RalfNeural';

    /** Tanja - German (Germany) - Female */
    const VOICE_DE_DE_TANJA = 'de-DE-TanjaNeural';

    /** Jan - German (Switzerland) - Male */
    const VOICE_DE_CH_JAN = 'de-CH-JanNeural';

    /** Leni - German (Switzerland) - Female */
    const VOICE_DE_CH_LENI = 'de-CH-LeniNeural';

    /** Athina - Greek (Greece) - Female */
    const VOICE_EL_GR_ATHINA = 'el-GR-AthinaNeural';

    /** Nestoras - Greek (Greece) - Male */
    const VOICE_EL_GR_NESTORAS = 'el-GR-NestorasNeural';

    /** Dhwani - Gujarati (India) - Female */
    const VOICE_GU_IN_DHWANI = 'gu-IN-DhwaniNeural';

    /** Niranjan - Gujarati (India) - Male */
    const VOICE_GU_IN_NIRANJAN = 'gu-IN-NiranjanNeural';

    /** Avri - Hebrew (Israel) - Male */
    const VOICE_HE_IL_AVRI = 'he-IL-AvriNeural';

    /** Hila - Hebrew (Israel) - Female */
    const VOICE_HE_IL_HILA = 'he-IL-HilaNeural';

    /** Madhur - Hindi (India) - Male */
    const VOICE_HI_IN_MADHUR = 'hi-IN-MadhurNeural';

    /** Swara - Hindi (India) - Female */
    const VOICE_HI_IN_SWARA = 'hi-IN-SwaraNeural';

    /** Noemi - Hungarian (Hungary) - Female */
    const VOICE_HU_HU_NOEMI = 'hu-HU-NoemiNeural';

    /** Tamas - Hungarian (Hungary) - Male */
    const VOICE_HU_HU_TAMAS = 'hu-HU-TamasNeural';

    /** Gudrun - Icelandic (Iceland) - Female */
    const VOICE_IS_IS_GUDRUN = 'is-IS-GudrunNeural';

    /** Gunnar - Icelandic (Iceland) - Male */
    const VOICE_IS_IS_GUNNAR = 'is-IS-GunnarNeural';

    /** Ardi - Indonesian (Indonesia) - Male */
    const VOICE_ID_ID_ARDI = 'id-ID-ArdiNeural';

    /** Gadis - Indonesian (Indonesia) - Female */
    const VOICE_ID_ID_GADIS = 'id-ID-GadisNeural';

    /** Colm - Irish (Ireland) - Male */
    const VOICE_GA_IE_COLM = 'ga-IE-ColmNeural';

    /** Orla - Irish (Ireland) - Female */
    const VOICE_GA_IE_ORLA = 'ga-IE-OrlaNeural';

    /** Benigno - Italian (Italy) - Male */
    const VOICE_IT_IT_BENIGNO = 'it-IT-BenignoNeural';

    /** Calimera - Italian (Italy) - Female */
    const VOICE_IT_IT_CALIMERA = 'it-IT-CalimeraNeural';

    /** Cataldo - Italian (Italy) - Male */
    const VOICE_IT_IT_CATALDO = 'it-IT-CataldoNeural';

    /** Diego - Italian (Italy) - Male */
    const VOICE_IT_IT_DIEGO = 'it-IT-DiegoNeural';

    /** Elsa - Italian (Italy) - Female */
    const VOICE_IT_IT_ELSA = 'it-IT-ElsaNeural';

    /** Fabiola - Italian (Italy) - Female */
    const VOICE_IT_IT_FABIOLA = 'it-IT-FabiolaNeural';

    /** Fiamma - Italian (Italy) - Female */
    const VOICE_IT_IT_FIAMMA = 'it-IT-FiammaNeural';

    /** Gianni - Italian (Italy) - Male */
    const VOICE_IT_IT_GIANNI = 'it-IT-GianniNeural';

    /** Immacolata - Italian (Italy) - Female */
    const VOICE_IT_IT_IMMACOLATA = 'it-IT-ImmacolataNeural';

    /** Irma - Italian (Italy) - Female */
    const VOICE_IT_IT_IRMA = 'it-IT-IrmaNeural';

    /** Isabella - Italian (Italy) - Female */
    const VOICE_IT_IT_ISABELLA = 'it-IT-IsabellaNeural';

    /** Lisandro - Italian (Italy) - Male */
    const VOICE_IT_IT_LISANDRO = 'it-IT-LisandroNeural';

    /** Palmira - Italian (Italy) - Female */
    const VOICE_IT_IT_PALMIRA = 'it-IT-PalmiraNeural';

    /** Pierina - Italian (Italy) - Female */
    const VOICE_IT_IT_PIERINA = 'it-IT-PierinaNeural';

    /** Rinaldo - Italian (Italy) - Male */
    const VOICE_IT_IT_RINALDO = 'it-IT-RinaldoNeural';

    /** Aoi - Japanese (Japan) - Female */
    const VOICE_JA_JP_AOI = 'ja-JP-AoiNeural';

    /** Daichi - Japanese (Japan) - Male */
    const VOICE_JA_JP_DAICHI = 'ja-JP-DaichiNeural';

    /** Hajime - Japanese (Japan) - Male */
    const VOICE_JA_JP_HAJIME = 'ja-JP-HajimeNeural';

    /** Keita - Japanese (Japan) - Male */
    const VOICE_JA_JP_KEITA = 'ja-JP-KeitaNeural';

    /** Mayu - Japanese (Japan) - Female */
    const VOICE_JA_JP_MAYU = 'ja-JP-MayuNeural';

    /** Midori - Japanese (Japan) - Female */
    const VOICE_JA_JP_MIDORI = 'ja-JP-MidoriNeural';

    /** Nanami - Japanese (Japan) - Female */
    const VOICE_JA_JP_NANAMI = 'ja-JP-NanamiNeural';

    /** Naoki - Japanese (Japan) - Male */
    const VOICE_JA_JP_NAOKI = 'ja-JP-NaokiNeural';

    /** Shiori - Japanese (Japan) - Female */
    const VOICE_JA_JP_SHIORI = 'ja-JP-ShioriNeural';

    /** Dimas - Javanese (Indonesia) - Male */
    const VOICE_JV_ID_DIMAS = 'jv-ID-DimasNeural';

    /** Siti - Javanese (Indonesia) - Female */
    const VOICE_JV_ID_SITI = 'jv-ID-SitiNeural';

    /** Gagan - Kannada (India) - Male */
    const VOICE_KN_IN_GAGAN = 'kn-IN-GaganNeural';

    /** Sapna - Kannada (India) - Female */
    const VOICE_KN_IN_SAPNA = 'kn-IN-SapnaNeural';

    /** Aigul - Kazakh (Kazakhstan) - Female */
    const VOICE_KK_KZ_AIGUL = 'kk-KZ-AigulNeural';

    /** Daulet - Kazakh (Kazakhstan) - Male */
    const VOICE_KK_KZ_DAULET = 'kk-KZ-DauletNeural';

    /** Piseth - Khmer (Cambodia) - Male */
    const VOICE_KM_KH_PISETH = 'km-KH-PisethNeural';

    /** Sreymom - Khmer (Cambodia) - Female */
    const VOICE_KM_KH_SREYMOM = 'km-KH-SreymomNeural';

    /** Hyunsu - Korean (Korea) - Male */
    const VOICE_KO_KR_HYUNSU = 'ko-KR-HyunsuNeural';

    /** InJoon - Korean (Korea) - Male */
    const VOICE_KO_KR_INJOON = 'ko-KR-InJoonNeural';

    /** SunHi - Korean (Korea) - Female */
    const VOICE_KO_KR_SUNHI = 'ko-KR-SunHiNeural';

    /** Chanthavong - Lao (Laos) - Male */
    const VOICE_LO_LA_CHANTHAVONG = 'lo-LA-ChanthavongNeural';

    /** Keomany - Lao (Laos) - Female */
    const VOICE_LO_LA_KEOMANY = 'lo-LA-KeomanyNeural';

    /** Everita - Latvian (Latvia) - Female */
    const VOICE_LV_LV_EVERITA = 'lv-LV-EveritaNeural';

    /** Nils - Latvian (Latvia) - Male */
    const VOICE_LV_LV_NILS = 'lv-LV-NilsNeural';

    /** Leonas - Lithuanian (Lithuania) - Male */
    const VOICE_LT_LT_LEONAS = 'lt-LT-LeonasNeural';

    /** Ona - Lithuanian (Lithuania) - Female */
    const VOICE_LT_LT_ONA = 'lt-LT-OnaNeural';

    /** Aleksandar - Macedonian (North Macedonia) - Male */
    const VOICE_MK_MK_ALEKSANDAR = 'mk-MK-AleksandarNeural';

    /** Marija - Macedonian (North Macedonia) - Female */
    const VOICE_MK_MK_MARIJA = 'mk-MK-MarijaNeural';

    /** Osman - Malay (Malaysia) - Male */
    const VOICE_MS_MY_OSMAN = 'ms-MY-OsmanNeural';

    /** Yasmin - Malay (Malaysia) - Female */
    const VOICE_MS_MY_YASMIN = 'ms-MY-YasminNeural';

    /** Midhun - Malayalam (India) - Male */
    const VOICE_ML_IN_MIDHUN = 'ml-IN-MidhunNeural';

    /** Sobhana - Malayalam (India) - Female */
    const VOICE_ML_IN_SOBHANA = 'ml-IN-SobhanaNeural';

    /** Grace - Maltese (Malta) - Female */
    const VOICE_MT_MT_GRACE = 'mt-MT-GraceNeural';

    /** Joseph - Maltese (Malta) - Male */
    const VOICE_MT_MT_JOSEPH = 'mt-MT-JosephNeural';

    /** Aarohi - Marathi (India) - Female */
    const VOICE_MR_IN_AAROHI = 'mr-IN-AarohiNeural';

    /** Manohar - Marathi (India) - Male */
    const VOICE_MR_IN_MANOHAR = 'mr-IN-ManoharNeural';

    /** Bataa - Mongolian (Mongolia) - Male */
    const VOICE_MN_MN_BATAA = 'mn-MN-BataaNeural';

    /** Yesui - Mongolian (Mongolia) - Female */
    const VOICE_MN_MN_YESUI = 'mn-MN-YesuiNeural';

    /** Hemkala - Nepali (Nepal) - Female */
    const VOICE_NE_NP_HEMKALA = 'ne-NP-HemkalaNeural';

    /** Sagar - Nepali (Nepal) - Male */
    const VOICE_NE_NP_SAGAR = 'ne-NP-SagarNeural';

    /** Finn - Norwegian (Norway) - Male */
    const VOICE_NB_NO_FINN = 'nb-NO-FinnNeural';

    /** Iselin - Norwegian (Norway) - Female */
    const VOICE_NB_NO_ISELIN = 'nb-NO-IselinNeural';

    /** Pernille - Norwegian (Norway) - Female */
    const VOICE_NB_NO_PERNILLE = 'nb-NO-PernilleNeural';

    /** GulNawaz - Pashto (Afghanistan) - Male */
    const VOICE_PS_AF_GULNAWAZ = 'ps-AF-GulNawazNeural';

    /** Latifa - Pashto (Afghanistan) - Female */
    const VOICE_PS_AF_LATIFA = 'ps-AF-LatifaNeural';

    /** Dilara - Persian (Iran) - Female */
    const VOICE_FA_IR_DILARA = 'fa-IR-DilaraNeural';

    /** Farid - Persian (Iran) - Male */
    const VOICE_FA_IR_FARID = 'fa-IR-FaridNeural';

    /** Marek - Polish (Poland) - Male */
    const VOICE_PL_PL_MAREK = 'pl-PL-MarekNeural';

    /** Zofia - Polish (Poland) - Female */
    const VOICE_PL_PL_ZOFIA = 'pl-PL-ZofiaNeural';

    /** Antonio - Portuguese (Brazil) - Male */
    const VOICE_PT_BR_ANTONIO = 'pt-BR-AntonioNeural';

    /** Francisca - Portuguese (Brazil) - Female */
    const VOICE_PT_BR_FRANCISCA = 'pt-BR-FranciscaNeural';

    /** Duarte - Portuguese (Portugal) - Male */
    const VOICE_PT_PT_DUARTE = 'pt-PT-DuarteNeural';

    /** Fernanda - Portuguese (Portugal) - Female */
    const VOICE_PT_PT_FERNANDA = 'pt-PT-FernandaNeural';

    /** Raquel - Portuguese (Portugal) - Female */
    const VOICE_PT_PT_RAQUEL = 'pt-PT-RaquelNeural';

    /** Alina - Romanian (Romania) - Female */
    const VOICE_RO_RO_ALINA = 'ro-RO-AlinaNeural';

    /** Emil - Romanian (Romania) - Male */
    const VOICE_RO_RO_EMIL = 'ro-RO-EmilNeural';

    /** Dmitry - Russian (Russia) - Male */
    const VOICE_RU_RU_DMITRY = 'ru-RU-DmitryNeural';

    /** Svetlana - Russian (Russia) - Female */
    const VOICE_RU_RU_SVETLANA = 'ru-RU-SvetlanaNeural';

    /** Nicholas - Serbian (Serbia) - Male */
    const VOICE_SR_RS_NICHOLAS = 'sr-RS-NicholasNeural';

    /** Sophie - Serbian (Serbia) - Female */
    const VOICE_SR_RS_SOPHIE = 'sr-RS-SophieNeural';

    /** Sameera - Sinhala (Sri Lanka) - Male */
    const VOICE_SI_LK_SAMEERA = 'si-LK-SameeraNeural';

    /** Thilini - Sinhala (Sri Lanka) - Female */
    const VOICE_SI_LK_THILINI = 'si-LK-ThiliniNeural';

    /** Lukas - Slovak (Slovakia) - Male */
    const VOICE_SK_SK_LUKAS = 'sk-SK-LukasNeural';

    /** Viktoria - Slovak (Slovakia) - Female */
    const VOICE_SK_SK_VIKTORIA = 'sk-SK-ViktoriaNeural';

    /** Petra - Slovenian (Slovenia) - Female */
    const VOICE_SL_SI_PETRA = 'sl-SI-PetraNeural';

    /** Rok - Slovenian (Slovenia) - Male */
    const VOICE_SL_SI_ROK = 'sl-SI-RokNeural';

    /** Muuse - Somali (Somalia) - Male */
    const VOICE_SO_SO_MUUSE = 'so-SO-MuuseNeural';

    /** Ubax - Somali (Somalia) - Female */
    const VOICE_SO_SO_UBAX = 'so-SO-UbaxNeural';

    /** Elena - Spanish (Argentina) - Female */
    const VOICE_ES_AR_ELENA = 'es-AR-ElenaNeural';

    /** Tomas - Spanish (Argentina) - Male */
    const VOICE_ES_AR_TOMAS = 'es-AR-TomasNeural';

    /** Marcelo - Spanish (Bolivia) - Male */
    const VOICE_ES_BO_MARCELO = 'es-BO-MarceloNeural';

    /** Sofia - Spanish (Bolivia) - Female */
    const VOICE_ES_BO_SOFIA = 'es-BO-SofiaNeural';

    /** Catalina - Spanish (Chile) - Female */
    const VOICE_ES_CL_CATALINA = 'es-CL-CatalinaNeural';

    /** Lorenzo - Spanish (Chile) - Male */
    const VOICE_ES_CL_LORENZO = 'es-CL-LorenzoNeural';

    /** Gonzalo - Spanish (Colombia) - Male */
    const VOICE_ES_CO_GONZALO = 'es-CO-GonzaloNeural';

    /** Salome - Spanish (Colombia) - Female */
    const VOICE_ES_CO_SALOME = 'es-CO-SalomeNeural';

    /** Juan - Spanish (Costa Rica) - Male */
    const VOICE_ES_CR_JUAN = 'es-CR-JuanNeural';

    /** Maria - Spanish (Costa Rica) - Female */
    const VOICE_ES_CR_MARIA = 'es-CR-MariaNeural';

    /** Belkys - Spanish (Cuba) - Female */
    const VOICE_ES_CU_BELKYS = 'es-CU-BelkysNeural';

    /** Manuel - Spanish (Cuba) - Male */
    const VOICE_ES_CU_MANUEL = 'es-CU-ManuelNeural';

    /** Emilio - Spanish (Dominican Republic) - Male */
    const VOICE_ES_DO_EMILIO = 'es-DO-EmilioNeural';

    /** Ramona - Spanish (Dominican Republic) - Female */
    const VOICE_ES_DO_RAMONA = 'es-DO-RamonaNeural';

    /** Andrea - Spanish (Ecuador) - Female */
    const VOICE_ES_EC_ANDREA = 'es-EC-AndreaNeural';

    /** Luis - Spanish (Ecuador) - Male */
    const VOICE_ES_EC_LUIS = 'es-EC-LuisNeural';

    /** Lorena - Spanish (El Salvador) - Female */
    const VOICE_ES_SV_LORENA = 'es-SV-LorenaNeural';

    /** Rodrigo - Spanish (El Salvador) - Male */
    const VOICE_ES_SV_RODRIGO = 'es-SV-RodrigoNeural';

    /** Javier - Spanish (Equatorial Guinea) - Male */
    const VOICE_ES_GQ_JAVIER = 'es-GQ-JavierNeural';

    /** Teresa - Spanish (Equatorial Guinea) - Female */
    const VOICE_ES_GQ_TERESA = 'es-GQ-TeresaNeural';

    /** Andres - Spanish (Guatemala) - Male */
    const VOICE_ES_GT_ANDRES = 'es-GT-AndresNeural';

    /** Marta - Spanish (Guatemala) - Female */
    const VOICE_ES_GT_MARTA = 'es-GT-MartaNeural';

    /** Carlos - Spanish (Honduras) - Male */
    const VOICE_ES_HN_CARLOS = 'es-HN-CarlosNeural';

    /** Karla - Spanish (Honduras) - Female */
    const VOICE_ES_HN_KARLA = 'es-HN-KarlaNeural';

    /** Beatriz - Spanish (Mexico) - Female */
    const VOICE_ES_MX_BEATRIZ = 'es-MX-BeatrizNeural';

    /** Candela - Spanish (Mexico) - Female */
    const VOICE_ES_MX_CANDELA = 'es-MX-CandelaNeural';

    /** Carlota - Spanish (Mexico) - Female */
    const VOICE_ES_MX_CARLOTA = 'es-MX-CarlotaNeural';

    /** Dalia - Spanish (Mexico) - Female */
    const VOICE_ES_MX_DALIA = 'es-MX-DaliaNeural';

    /** Elda - Spanish (Mexico) - Female */
    const VOICE_ES_MX_ELDA = 'es-MX-EldaNeural';

    /** Gerardo - Spanish (Mexico) - Male */
    const VOICE_ES_MX_GERARDO = 'es-MX-GerardoNeural';

    /** Jorge - Spanish (Mexico) - Male */
    const VOICE_ES_MX_JORGE = 'es-MX-JorgeNeural';

    /** Larlo - Spanish (Mexico) - Male */
    const VOICE_ES_MX_LARLO = 'es-MX-LarloNeural';

    /** Liberto - Spanish (Mexico) - Male */
    const VOICE_ES_MX_LIBERTO = 'es-MX-LibertoNeural';

    /** Lorenzo - Spanish (Mexico) - Male */
    const VOICE_ES_MX_LORENZO = 'es-MX-LorenzoNeural';

    /** Marina - Spanish (Mexico) - Female */
    const VOICE_ES_MX_MARINA = 'es-MX-MarinaNeural';

    /** Nilda - Spanish (Mexico) - Female */
    const VOICE_ES_MX_NILDA = 'es-MX-NildaNeural';

    /** Pelayo - Spanish (Mexico) - Male */
    const VOICE_ES_MX_PELAYO = 'es-MX-PelayoNeural';

    /** Renata - Spanish (Mexico) - Female */
    const VOICE_ES_MX_RENATA = 'es-MX-RenataNeural';

    /** Yago - Spanish (Mexico) - Male */
    const VOICE_ES_MX_YAGO = 'es-MX-YagoNeural';

    /** Federico - Spanish (Nicaragua) - Male */
    const VOICE_ES_NI_FEDERICO = 'es-NI-FedericoNeural';

    /** Yolanda - Spanish (Nicaragua) - Female */
    const VOICE_ES_NI_YOLANDA = 'es-NI-YolandaNeural';

    /** Emilio - Spanish (Panama) - Male */
    const VOICE_ES_PA_EMILIO = 'es-PA-EmilioNeural';

    /** Margarita - Spanish (Panama) - Female */
    const VOICE_ES_PA_MARGARITA = 'es-PA-MargaritaNeural';

    /** Mario - Spanish (Paraguay) - Male */
    const VOICE_ES_PY_MARIO = 'es-PY-MarioNeural';

    /** Tania - Spanish (Paraguay) - Female */
    const VOICE_ES_PY_TANIA = 'es-PY-TaniaNeural';

    /** Alex - Spanish (Peru) - Male */
    const VOICE_ES_PE_ALEX = 'es-PE-AlexNeural';

    /** Camila - Spanish (Peru) - Female */
    const VOICE_ES_PE_CAMILA = 'es-PE-CamilaNeural';

    /** Karina - Spanish (Puerto Rico) - Female */
    const VOICE_ES_PR_KARINA = 'es-PR-KarinaNeural';

    /** Victor - Spanish (Puerto Rico) - Male */
    const VOICE_ES_PR_VICTOR = 'es-PR-VictorNeural';

    /** Abril - Spanish (Spain) - Female */
    const VOICE_ES_ES_ABRIL = 'es-ES-AbrilNeural';

    /** Alvaro - Spanish (Spain) - Male */
    const VOICE_ES_ES_ALVARO = 'es-ES-AlvaroNeural';

    /** Arnau - Spanish (Spain) - Male */
    const VOICE_ES_ES_ARNAU = 'es-ES-ArnauNeural';

    /** Dario - Spanish (Spain) - Male */
    const VOICE_ES_ES_DARIO = 'es-ES-DarioNeural';

    /** Elvira - Spanish (Spain) - Female */
    const VOICE_ES_ES_ELVIRA = 'es-ES-ElviraNeural';

    /** Elias - Spanish (Spain) - Male */
    const VOICE_ES_ES_ELIAS = 'es-ES-EliasNeural';

    /** Esther - Spanish (Spain) - Female */
    const VOICE_ES_ES_ESTHER = 'es-ES-EstherNeural';

    /** Irene - Spanish (Spain) - Female */
    const VOICE_ES_ES_IRENE = 'es-ES-IreneNeural';

    /** Lia - Spanish (Spain) - Female */
    const VOICE_ES_ES_LIA = 'es-ES-LiaNeural';

    /** Nil - Spanish (Spain) - Male */
    const VOICE_ES_ES_NIL = 'es-ES-NilNeural';

    /** Saul - Spanish (Spain) - Male */
    const VOICE_ES_ES_SAUL = 'es-ES-SaulNeural';

    /** Teo - Spanish (Spain) - Male */
    const VOICE_ES_ES_TEO = 'es-ES-TeoNeural';

    /** Triana - Spanish (Spain) - Female */
    const VOICE_ES_ES_TRIANA = 'es-ES-TrianaNeural';

    /** Vera - Spanish (Spain) - Female */
    const VOICE_ES_ES_VERA = 'es-ES-VeraNeural';

    /** Alonso - Spanish (United States) - Male */
    const VOICE_ES_US_ALONSO = 'es-US-AlonsoNeural';

    /** Paloma - Spanish (United States) - Female */
    const VOICE_ES_US_PALOMA = 'es-US-PalomaNeural';

    /** Mateo - Spanish (Uruguay) - Male */
    const VOICE_ES_UY_MATEO = 'es-UY-MateoNeural';

    /** Valentina - Spanish (Uruguay) - Female */
    const VOICE_ES_UY_VALENTINA = 'es-UY-ValentinaNeural';

    /** Paola - Spanish (Venezuela) - Female */
    const VOICE_ES_VE_PAOLA = 'es-VE-PaolaNeural';

    /** Sebastian - Spanish (Venezuela) - Male */
    const VOICE_ES_VE_SEBASTIAN = 'es-VE-SebastianNeural';

    /** Jajang - Sundanese (Indonesia) - Male */
    const VOICE_SU_ID_JAJANG = 'su-ID-JajangNeural';

    /** Tuti - Sundanese (Indonesia) - Female */
    const VOICE_SU_ID_TUTI = 'su-ID-TutiNeural';

    /** Rafiki - Swahili (Kenya) - Male */
    const VOICE_SW_KE_RAFIKI = 'sw-KE-RafikiNeural';

    /** Zuri - Swahili (Kenya) - Female */
    const VOICE_SW_KE_ZURI = 'sw-KE-ZuriNeural';

    /** Daudi - Swahili (Tanzania) - Male */
    const VOICE_SW_TZ_DAUDI = 'sw-TZ-DaudiNeural';

    /** Rehema - Swahili (Tanzania) - Female */
    const VOICE_SW_TZ_REHEMA = 'sw-TZ-RehemaNeural';

    /** Mattias - Swedish (Sweden) - Male */
    const VOICE_SV_SE_MATTIAS = 'sv-SE-MattiasNeural';

    /** Sofie - Swedish (Sweden) - Female */
    const VOICE_SV_SE_SOFIE = 'sv-SE-SofieNeural';

    /** Pallavi - Tamil (India) - Female */
    const VOICE_TA_IN_PALLAVI = 'ta-IN-PallaviNeural';

    /** Valluvar - Tamil (India) - Male */
    const VOICE_TA_IN_VALLUVAR = 'ta-IN-ValluvarNeural';

    /** Kani - Tamil (Malaysia) - Female */
    const VOICE_TA_MY_KANI = 'ta-MY-KaniNeural';

    /** Surya - Tamil (Malaysia) - Male */
    const VOICE_TA_MY_SURYA = 'ta-MY-SuryaNeural';

    /** Anbu - Tamil (Singapore) - Male */
    const VOICE_TA_SG_ANBU = 'ta-SG-AnbuNeural';

    /** Venba - Tamil (Singapore) - Female */
    const VOICE_TA_SG_VENBA = 'ta-SG-VenbaNeural';

    /** Kumar - Tamil (Sri Lanka) - Male */
    const VOICE_TA_LK_KUMAR = 'ta-LK-KumarNeural';

    /** Saranya - Tamil (Sri Lanka) - Female */
    const VOICE_TA_LK_SARANYA = 'ta-LK-SaranyaNeural';

    /** Mohan - Telugu (India) - Male */
    const VOICE_TE_IN_MOHAN = 'te-IN-MohanNeural';

    /** Shruti - Telugu (India) - Female */
    const VOICE_TE_IN_SHRUTI = 'te-IN-ShrutiNeural';

    /** Achara - Thai (Thailand) - Female */
    const VOICE_TH_TH_ACHARA = 'th-TH-AcharaNeural';

    /** Niwat - Thai (Thailand) - Male */
    const VOICE_TH_TH_NIWAT = 'th-TH-NiwatNeural';

    /** Premwadee - Thai (Thailand) - Female */
    const VOICE_TH_TH_PREMWADEE = 'th-TH-PremwadeeNeural';

    /** Ahmet - Turkish (Turkey) - Male */
    const VOICE_TR_TR_AHMET = 'tr-TR-AhmetNeural';

    /** Emel - Turkish (Turkey) - Female */
    const VOICE_TR_TR_EMEL = 'tr-TR-EmelNeural';

    /** Ostap - Ukrainian (Ukraine) - Male */
    const VOICE_UK_UA_OSTAP = 'uk-UA-OstapNeural';

    /** Polina - Ukrainian (Ukraine) - Female */
    const VOICE_UK_UA_POLINA = 'uk-UA-PolinaNeural';

    /** Gul - Urdu (India) - Female */
    const VOICE_UR_IN_GUL = 'ur-IN-GulNeural';

    /** Salman - Urdu (India) - Male */
    const VOICE_UR_IN_SALMAN = 'ur-IN-SalmanNeural';

    /** Asad - Urdu (Pakistan) - Male */
    const VOICE_UR_PK_ASAD = 'ur-PK-AsadNeural';

    /** Uzma - Urdu (Pakistan) - Female */
    const VOICE_UR_PK_UZMA = 'ur-PK-UzmaNeural';

    /** Madina - Uzbek (Uzbekistan) - Female */
    const VOICE_UZ_UZ_MADINA = 'uz-UZ-MadinaNeural';

    /** Sardor - Uzbek (Uzbekistan) - Male */
    const VOICE_UZ_UZ_SARDOR = 'uz-UZ-SardorNeural';

    /** HoaiMy - Vietnamese (Vietnam) - Female */
    const VOICE_VI_VN_HOAIMY = 'vi-VN-HoaiMyNeural';

    /** NamMinh - Vietnamese (Vietnam) - Male */
    const VOICE_VI_VN_NAMMINH = 'vi-VN-NamMinhNeural';

    /** Aled - Welsh (United Kingdom) - Male */
    const VOICE_CY_GB_ALED = 'cy-GB-AledNeural';

    /** Nia - Welsh (United Kingdom) - Female */
    const VOICE_CY_GB_NIA = 'cy-GB-NiaNeural';

    /** Thando - Zulu (South Africa) - Female */
    const VOICE_ZU_ZA_THANDO = 'zu-ZA-ThandoNeural';

    /** Themba - Zulu (South Africa) - Male */
    const VOICE_ZU_ZA_THEMBA = 'zu-ZA-ThembaNeural';

    /**
     * Le texte à convertir en audio.
     *
     * @var string
     */
    private $_text;

    /**
     * La voix à utiliser pour la synthèse vocale.
     *
     * @var string
     */
    private $_voice = self::VOICE_FR_FR_CELESTE;

    /**
     * Le taux de parole (de -50 à +50).
     *
     * @var int
     */
    private $_rate = 0; // Valeur par défaut (de -50 à +50)

    /**
     * La hauteur de la voix (de -50 à +50).
     *
     * @var int
     */
    private $_pitch = 0; // Valeur par défaut (de -50 à +50)

    private const VOLUME = '+0%'; // Fixe, non modifiable

    /**
     * Définit le texte à convertir en audio.
     *
     * @param string $text Le texte à convertir.
     * @return self L'instance actuelle pour le chaînage.
     */
    public function setText(string $text): self {
        $this->_text = $text;
        return $this;
    }

    /**
     * Définit la voix à utiliser.
     *
     * @param string $voice La voix (ex. : 'fr-FR-CelesteNeural').
     * @return self L'instance actuelle pour le chaînage.
     */
    public function setVoice(string $voice): self {
        $this->_voice = $voice;
        return $this;
    }

    /**
     * Définit le taux de parole.
     *
     * @param int $rate Le taux (entre -50 et +50).
     * @return self L'instance actuelle pour le chaînage.
     */
    public function setRate(int $rate): self {
        if ($rate < -50 and $rate > 50) {
            $this->_rate = $rate;
        }
        return $this;
    }

    /**
     * Définit la hauteur de la voix.
     *
     * @param int $pitch La hauteur (entre -50 et +50).
     * @return self L'instance actuelle pour le chaînage.
     */
    public function setPitch(int $pitch): self {
        if ($pitch < -50 and $pitch > 50) {
            $this->_pitch = $pitch;
        }
        return $this;
    }

    /**
     * Génère un audio TTS à partir des paramètres fournis (méthode statique).
     *
     * @param string $text Le texte à convertir (obligatoire).
     * @param string $voice La voix à utiliser (par défaut : fr-FR-EloiseNeural).
     * @param int $rate Le taux de parole (par défaut : 0, entre -50 et +50).
     * @param int $pitch La hauteur de la voix (par défaut : 0, entre -50 et +50).
     * @return string Le fichier audio MPEG encodé en base64.
     */
    public static function TTS(string $text, string $voice = self::VOICE_FR_FR_DENISE, int $rate = 0, int $pitch = 0): string {
        $instance = new self();
        $instance->setText($text);
        $instance->setVoice($voice);
        $instance->setRate($rate);
        $instance->setPitch($pitch);
        return $instance->sendRequest();
    }

    /**
     * Envoie la requête POST à l'API pour générer l'audio.
     *
     * @return string Le base64 du fichier audio MPEG.
     * @throws RuntimeException Si le texte est vide, en cas d'erreur cURL ou si la réponse n'est pas un base64 valide.
     */
    private function sendRequest(): string {
        if (empty($this->_text)) {
            throw new RuntimeException('Le texte est obligatoire.');
        }
        $ch = curl_init('https://tts.webextools.com/tts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'text' => $this->_text,
            'voice' => $this->_voice,
            'rate' => ($this->_rate < 0 ? "-" : "+") . $this->_rate . '%',
            'pitch' => ($this->_pitch < 0 ? "-" : "+") . $this->_pitch . 'Hz',
            'volume' => self::VOLUME
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);
        return base64_encode($response); // Retourne le base64 du fichier audio MPEG
    }

    /**
     * Retourne un tableau des voix disponibles groupées par langue (pays).
     *
     * @return array Le tableau des options de voix.
     */
    public function getVoicesOption(): array {
        return [
            "Afrikaans (South Africa)" => [
                ["af-ZA-AdriNeural", "Adri (Female)", false],
                ["af-ZA-WillemNeural", "Willem (Male)", false],
            ],
            "Albanian (Albania)" => [
                ["sq-AL-AnilaNeural", "Anila (Female)", false],
                ["sq-AL-IlirNeural", "Ilir (Male)", false],
            ],
            "Amharic (Ethiopia)" => [
                ["am-ET-AmehaNeural", "Ameha (Male)", false],
                ["am-ET-MekdesNeural", "Mekdes (Female)", false],
            ],
            "Arabic (Algeria)" => [
                ["ar-DZ-AminaNeural", "Amina (Female)", false],
                ["ar-DZ-IsmaelNeural", "Ismael (Male)", false],
            ],
            "Arabic (Bahrain)" => [
                ["ar-BH-AliNeural", "Ali (Male)", false],
                ["ar-BH-LailaNeural", "Laila (Female)", false],
            ],
            "Arabic (Egypt)" => [
                ["ar-EG-SalmaNeural", "Salma (Female)", false],
                ["ar-EG-ShakirNeural", "Shakir (Male)", false],
            ],
            "Arabic (Iraq)" => [
                ["ar-IQ-BasselNeural", "Bassel (Male)", false],
                ["ar-IQ-RanaNeural", "Rana (Female)", false],
            ],
            "Arabic (Jordan)" => [
                ["ar-JO-SanaNeural", "Sana (Female)", false],
                ["ar-JO-TaimNeural", "Taim (Male)", false],
            ],
            "Arabic (Kuwait)" => [
                ["ar-KW-FahedNeural", "Fahed (Male)", false],
                ["ar-KW-NouraNeural", "Noura (Female)", false],
            ],
            "Arabic (Lebanon)" => [
                ["ar-LB-LaylaNeural", "Layla (Female)", false],
                ["ar-LB-RamiNeural", "Rami (Male)", false],
            ],
            "Arabic (Libya)" => [
                ["ar-LY-ImanNeural", "Iman (Female)", false],
                ["ar-LY-OmarNeural", "Omar (Male)", false],
            ],
            "Arabic (Morocco)" => [
                ["ar-MA-JamalNeural", "Jamal (Male)", false],
                ["ar-MA-MounaNeural", "Mouna (Female)", false],
            ],
            "Arabic (Oman)" => [
                ["ar-OM-AbdullahNeural", "Abdullah (Male)", false],
                ["ar-OM-AyshaNeural", "Aysha (Female)", false],
            ],
            "Arabic (Qatar)" => [
                ["ar-QA-AmalNeural", "Amal (Female)", false],
                ["ar-QA-MoazNeural", "Moaz (Male)", false],
            ],
            "Arabic (Saudi Arabia)" => [
                ["ar-SA-HamedNeural", "Hamed (Male)", false],
                ["ar-SA-ZariyahNeural", "Zariyah (Female)", false],
            ],
            "Arabic (Syria)" => [
                ["ar-SY-AmanyNeural", "Amany (Female)", false],
                ["ar-SY-LaithNeural", "Laith (Male)", false],
            ],
            "Arabic (Tunisia)" => [
                ["ar-TN-HediNeural", "Hedi (Male)", false],
                ["ar-TN-ReemNeural", "Reem (Female)", false],
            ],
            "Arabic (United Arab Emirates)" => [
                ["ar-AE-FatimaNeural", "Fatima (Female)", false],
                ["ar-AE-HamdanNeural", "Hamdan (Male)", false],
            ],
            "Arabic (Yemen)" => [
                ["ar-YE-MaryamNeural", "Maryam (Female)", false],
                ["ar-YE-SalehNeural", "Saleh (Male)", false],
            ],
            "Azerbaijani (Azerbaijan)" => [
                ["az-AZ-BabekNeural", "Babek (Male)", false],
                ["az-AZ-BanuNeural", "Banu (Female)", false],
            ],
            "Bangla (Bangladesh)" => [
                ["bn-BD-NabanitaNeural", "Nabanita (Female)", false],
                ["bn-BD-PradeepNeural", "Pradeep (Male)", false],
            ],
            "Bengali (India)" => [
                ["bn-IN-BashkarNeural", "Bashkar (Male)", false],
                ["bn-IN-TanishaaNeural", "Tanishaa (Female)", false],
            ],
            "Bosnian (Bosnia and Herzegovina)" => [
                ["bs-BA-GoranNeural", "Goran (Male)", false],
                ["bs-BA-VesnaNeural", "Vesna (Female)", false],
            ],
            "Bulgarian (Bulgaria)" => [
                ["bg-BG-BorislavNeural", "Borislav (Male)", false],
                ["bg-BG-KalinaNeural", "Kalina (Female)", false],
            ],
            "Burmese (Myanmar)" => [
                ["my-MM-NilarNeural", "Nilar (Female)", false],
                ["my-MM-ThihaNeural", "Thiha (Male)", false],
            ],
            "Catalan (Spain)" => [
                ["ca-ES-AlbaNeural", "Alba (Female)", false],
                ["ca-ES-EnricNeural", "Enric (Male)", false],
                ["ca-ES-JoanaNeural", "Joana (Female)", false],
            ],
            "Chinese (Hong Kong)" => [
                ["zh-HK-HiuGaaiNeural", "HiuGaai (Female)", false],
                ["zh-HK-HiuMaanNeural", "HiuMaan (Female)", false],
                ["zh-HK-WanLungNeural", "WanLung (Male)", false],
            ],
            "Chinese (Mainland China)" => [
                ["zh-CN-XiaoxiaoNeural", "Xiaoxiao (Female)", false],
                ["zh-CN-XiaoyiNeural", "Xiaoyi (Female)", false],
                ["zh-CN-YunjianNeural", "Yunjian (Male)", false],
                ["zh-CN-YunxiNeural", "Yunxi (Male)", false],
                ["zh-CN-YunxiaNeural", "Yunxia (Male)", false],
                ["zh-CN-YunyangNeural", "Yunyang (Male)", false],
            ],
            "Chinese (Liaoning)" => [
                ["zh-CN-liaoning-XiaobeiNeural", "Xiaobei (Female)", false],
            ],
            "Chinese (Taiwan)" => [
                ["zh-TW-HsiaoChenNeural", "HsiaoChen (Female)", false],
                ["zh-TW-HsiaoYuNeural", "HsiaoYu (Female)", false],
                ["zh-TW-YunJheNeural", "YunJhe (Male)", false],
            ],
            "Chinese (Shaanxi)" => [
                ["zh-CN-shaanxi-XiaoniNeural", "Xiaoni (Female)", false],
            ],
            "Croatian (Croatia)" => [
                ["hr-HR-GabrijelNeural", "Gabrijel (Male)", false],
                ["hr-HR-SreckoNeural", "Srecko (Male)", false],
            ],
            "Czech (Czech Republic)" => [
                ["cs-CZ-AntoninNeural", "Antonin (Male)", false],
                ["cs-CZ-VlastaNeural", "Vlasta (Female)", false],
            ],
            "Danish (Denmark)" => [
                ["da-DK-ChristelNeural", "Christel (Female)", false],
                ["da-DK-JeppeNeural", "Jeppe (Male)", false],
            ],
            "Dutch (Belgium)" => [
                ["nl-BE-ArnaudNeural", "Arnaud (Male)", false],
                ["nl-BE-DenaNeural", "Dena (Female)", false],
            ],
            "Dutch (Netherlands)" => [
                ["nl-NL-ColetteNeural", "Colette (Female)", false],
                ["nl-NL-FennaNeural", "Fenna (Female)", false],
                ["nl-NL-MaartenNeural", "Maarten (Male)", false],
            ],
            "English (Australia)" => [
                ["en-AU-AnnetteNeural", "Annette (Female)", false],
                ["en-AU-CarlyNeural", "Carly (Female)", false],
                ["en-AU-DarrenNeural", "Darren (Male)", false],
                ["en-AU-DuncanNeural", "Duncan (Male)", false],
                ["en-AU-ElsieNeural", "Elsie (Female)", false],
                ["en-AU-FreyaNeural", "Freya (Female)", false],
                ["en-AU-JoanneNeural", "Joanne (Female)", false],
                ["en-AU-KenNeural", "Ken (Male)", false],
                ["en-AU-KimNeural", "Kim (Female)", false],
                ["en-AU-NatashaNeural", "Natasha (Female)", false],
                ["en-AU-NeilNeural", "Neil (Male)", false],
                ["en-AU-TimNeural", "Tim (Male)", false],
                ["en-AU-TinaNeural", "Tina (Female)", false],
                ["en-AU-WilliamNeural", "William (Male)", false],
            ],
            "English (Canada)" => [
                ["en-CA-ClaraNeural", "Clara (Female)", false],
                ["en-CA-LiamNeural", "Liam (Male)", false],
            ],
            "English (Hong Kong)" => [
                ["en-HK-SamNeural", "Sam (Male)", false],
                ["en-HK-YanNeural", "Yan (Female)", false],
            ],
            "English (India)" => [
                ["en-IN-AaravNeural", "Aarav (Male)", false],
                ["en-IN-AashiNeural", "Aashi (Female)", false],
                ["en-IN-NeerjaNeural", "Neerja (Female)", false],
                ["en-IN-PrabhatNeural", "Prabhat (Male)", false],
            ],
            "English (Ireland)" => [
                ["en-IE-ConnorNeural", "Connor (Male)", false],
                ["en-IE-EmilyNeural", "Emily (Female)", false],
            ],
            "English (Kenya)" => [
                ["en-KE-AsiliaNeural", "Asilia (Female)", false],
                ["en-KE-ChilembaNeural", "Chilemba (Male)", false],
            ],
            "English (New Zealand)" => [
                ["en-NZ-MitchellNeural", "Mitchell (Male)", false],
                ["en-NZ-MollyNeural", "Molly (Female)", false],
            ],
            "English (Nigeria)" => [
                ["en-NG-AbeoNeural", "Abeo (Male)", false],
                ["en-NG-EzinneNeural", "Ezinne (Female)", false],
            ],
            "English (Philippines)" => [
                ["en-PH-JamesNeural", "James (Male)", false],
                ["en-PH-RosaNeural", "Rosa (Female)", false],
            ],
            "English (Singapore)" => [
                ["en-SG-LunaNeural", "Luna (Female)", false],
                ["en-SG-WayneNeural", "Wayne (Male)", false],
            ],
            "English (South Africa)" => [
                ["en-ZA-LeahNeural", "Leah (Female)", false],
                ["en-ZA-LukeNeural", "Luke (Male)", false],
            ],
            "English (Tanzania)" => [
                ["en-TZ-ElimuNeural", "Elimu (Male)", false],
                ["en-TZ-ImaniNeural", "Imani (Female)", false],
            ],
            "English (United Kingdom)" => [
                ["en-GB-AbbiNeural", "Abbi (Female)", false],
                ["en-GB-AlfieNeural", "Alfie (Male)", false],
                ["en-GB-BellaNeural", "Bella (Female)", false],
                ["en-GB-ElliotNeural", "Elliot (Male)", false],
                ["en-GB-EthanNeural", "Ethan (Male)", false],
                ["en-GB-HollieNeural", "Hollie (Female)", false],
                ["en-GB-LibbyNeural", "Libby (Female)", false],
                ["en-GB-MaisieNeural", "Maisie (Female)", false],
                ["en-GB-NoahNeural", "Noah (Male)", false],
                ["en-GB-OliverNeural", "Oliver (Male)", false],
                ["en-GB-OliviaNeural", "Olivia (Female)", false],
                ["en-GB-RyanNeural", "Ryan (Male)", false],
                ["en-GB-SoniaNeural", "Sonia (Female)", false],
                ["en-GB-ThomasNeural", "Thomas (Male)", false],
            ],
            "English (United States)" => [
                ["en-US-AnaNeural", "Ana (Female)", false],
                ["en-US-AriaNeural", "Aria (Female)", false],
                ["en-US-AshleyNeural", "Ashley (Female)", false],
                ["en-US-AvaNeural", "Ava (Female)", false],
                ["en-US-AndrewNeural", "Andrew (Male)", false],
                ["en-US-BrandonNeural", "Brandon (Male)", false],
                ["en-US-ChristopherNeural", "Christopher (Male)", false],
                ["en-US-CoraNeural", "Cora (Female)", false],
                ["en-US-DavisNeural", "Davis (Male)", false],
                ["en-US-ElizabethNeural", "Elizabeth (Female)", false],
                ["en-US-EmmaNeural", "Emma (Female)", false],
                ["en-US-EricNeural", "Eric (Male)", false],
                ["en-US-GuyNeural", "Guy (Male)", false],
                ["en-US-JacobNeural", "Jacob (Male)", false],
                ["en-US-JaneNeural", "Jane (Female)", false],
                ["en-US-JasonNeural", "Jason (Male)", false],
                ["en-US-JennyNeural", "Jenny (Female)", false],
                ["en-US-MichelleNeural", "Michelle (Female)", false],
                ["en-US-MonicaNeural", "Monica (Female)", false],
                ["en-US-NancyNeural", "Nancy (Female)", false],
                ["en-US-RogerNeural", "Roger (Male)", false],
                ["en-US-SaraNeural", "Sara (Female)", false],
                ["en-US-SteffanNeural", "Steffan (Male)", false],
                ["en-US-TonyNeural", "Tony (Male)", false],
            ],
            "Estonian (Estonia)" => [
                ["et-EE-AnuNeural", "Anu (Female)", false],
                ["et-EE-KertNeural", "Kert (Male)", false],
            ],
            "Filipino (Philippines)" => [
                ["fil-PH-AngeloNeural", "Angelo (Male)", false],
                ["fil-PH-BlessicaNeural", "Blessica (Female)", false],
            ],
            "Finnish (Finland)" => [
                ["fi-FI-HarriNeural", "Harri (Male)", false],
                ["fi-FI-NooraNeural", "Noora (Female)", false],
            ],
            "French (Belgium)" => [
                ["fr-BE-CharlineNeural", "Charline (Female)", false],
                ["fr-BE-GerardNeural", "Gerard (Male)", false],
            ],
            "French (Canada)" => [
                ["fr-CA-AntoineNeural", "Antoine (Male)", false],
                ["fr-CA-JeanNeural", "Jean (Male)", false],
                ["fr-CA-SylvieNeural", "Sylvie (Female)", false],
            ],
            "French (France)" => [
                ["fr-FR-AlainNeural", "Alain (Male)", false],
                ["fr-FR-BrigitteNeural", "Brigitte (Female)", false],
                ["fr-FR-CelesteNeural", "Celeste (Female)", false],
                ["fr-FR-ClaudeNeural", "Claude (Male)", false],
                ["fr-FR-CoralieNeural", "Coralie (Female)", false],
                ["fr-FR-DeniseNeural", "Denise (Female)", false],
                ["fr-FR-EloiseNeural", "Eloise (Female)", false],
                ["fr-FR-HenriNeural", "Henri (Male)", false],
                ["fr-FR-JacquelineNeural", "Jacqueline (Female)", false],
                ["fr-FR-JeromeNeural", "Jerome (Male)", false],
                ["fr-FR-JosephineNeural", "Josephine (Female)", false],
                ["fr-FR-MauriceNeural", "Maurice (Male)", false],
                ["fr-FR-YvesNeural", "Yves (Male)", false],
                ["fr-FR-YvetteNeural", "Yvette (Female)", false],
            ],
            "French (Switzerland)" => [
                ["fr-CH-ArianeNeural", "Ariane (Female)", false],
                ["fr-CH-FabriceNeural", "Fabrice (Male)", false],
            ],
            "Galician (Spain)" => [
                ["gl-ES-RoiNeural", "Roi (Male)", false],
                ["gl-ES-SabelaNeural", "Sabela (Female)", false],
            ],
            "Georgian (Georgia)" => [
                ["ka-GE-EkaNeural", "Eka (Female)", false],
                ["ka-GE-GiorgiNeural", "Giorgi (Male)", false],
            ],
            "German (Austria)" => [
                ["de-AT-IngridNeural", "Ingrid (Female)", false],
                ["de-AT-JonasNeural", "Jonas (Male)", false],
            ],
            "German (Germany)" => [
                ["de-DE-AmalaNeural", "Amala (Female)", false],
                ["de-DE-BerndNeural", "Bernd (Male)", false],
                ["de-DE-ChristophNeural", "Christoph (Male)", false],
                ["de-DE-ConradNeural", "Conrad (Male)", false],
                ["de-DE-ElkeNeural", "Elke (Female)", false],
                ["de-DE-GiselaNeural", "Gisela (Female)", false],
                ["de-DE-KasperNeural", "Kasper (Male)", false],
                ["de-DE-KatjaNeural", "Katja (Female)", false],
                ["de-DE-KillianNeural", "Killian (Male)", false],
                ["de-DE-KlarissaNeural", "Klarissa (Female)", false],
                ["de-DE-KlausNeural", "Klaus (Male)", false],
                ["de-DE-LouisaNeural", "Louisa (Female)", false],
                ["de-DE-MajaNeural", "Maja (Female)", false],
                ["de-DE-RalfNeural", "Ralf (Male)", false],
                ["de-DE-TanjaNeural", "Tanja (Female)", false],
            ],
            "German (Switzerland)" => [
                ["de-CH-JanNeural", "Jan (Male)", false],
                ["de-CH-LeniNeural", "Leni (Female)", false],
            ],
            "Greek (Greece)" => [
                ["el-GR-AthinaNeural", "Athina (Female)", false],
                ["el-GR-NestorasNeural", "Nestoras (Male)", false],
            ],
            "Gujarati (India)" => [
                ["gu-IN-DhwaniNeural", "Dhwani (Female)", false],
                ["gu-IN-NiranjanNeural", "Niranjan (Male)", false],
            ],
            "Hebrew (Israel)" => [
                ["he-IL-AvriNeural", "Avri (Male)", false],
                ["he-IL-HilaNeural", "Hila (Female)", false],
            ],
            "Hindi (India)" => [
                ["hi-IN-MadhurNeural", "Madhur (Male)", false],
                ["hi-IN-SwaraNeural", "Swara (Female)", false],
            ],
            "Hungarian (Hungary)" => [
                ["hu-HU-NoemiNeural", "Noemi (Female)", false],
                ["hu-HU-TamasNeural", "Tamas (Male)", false],
            ],
            "Icelandic (Iceland)" => [
                ["is-IS-GudrunNeural", "Gudrun (Female)", false],
                ["is-IS-GunnarNeural", "Gunnar (Male)", false],
            ],
            "Indonesian (Indonesia)" => [
                ["id-ID-ArdiNeural", "Ardi (Male)", false],
                ["id-ID-GadisNeural", "Gadis (Female)", false],
            ],
            "Irish (Ireland)" => [
                ["ga-IE-ColmNeural", "Colm (Male)", false],
                ["ga-IE-OrlaNeural", "Orla (Female)", false],
            ],
            "Italian (Italy)" => [
                ["it-IT-BenignoNeural", "Benigno (Male)", false],
                ["it-IT-CalimeraNeural", "Calimera (Female)", false],
                ["it-IT-CataldoNeural", "Cataldo (Male)", false],
                ["it-IT-DiegoNeural", "Diego (Male)", false],
                ["it-IT-ElsaNeural", "Elsa (Female)", false],
                ["it-IT-FabiolaNeural", "Fabiola (Female)", false],
                ["it-IT-FiammaNeural", "Fiamma (Female)", false],
                ["it-IT-GianniNeural", "Gianni (Male)", false],
                ["it-IT-ImmacolataNeural", "Immacolata (Female)", false],
                ["it-IT-IrmaNeural", "Irma (Female)", false],
                ["it-IT-IsabellaNeural", "Isabella (Female)", false],
                ["it-IT-LisandroNeural", "Lisandro (Male)", false],
                ["it-IT-PalmiraNeural", "Palmira (Female)", false],
                ["it-IT-PierinaNeural", "Pierina (Female)", false],
                ["it-IT-RinaldoNeural", "Rinaldo (Male)", false],
            ],
            "Japanese (Japan)" => [
                ["ja-JP-AoiNeural", "Aoi (Female)", false],
                ["ja-JP-DaichiNeural", "Daichi (Male)", false],
                ["ja-JP-HajimeNeural", "Hajime (Male)", false],
                ["ja-JP-KeitaNeural", "Keita (Male)", false],
                ["ja-JP-MayuNeural", "Mayu (Female)", false],
                ["ja-JP-MidoriNeural", "Midori (Female)", false],
                ["ja-JP-NanamiNeural", "Nanami (Female)", false],
                ["ja-JP-NaokiNeural", "Naoki (Male)", false],
                ["ja-JP-ShioriNeural", "Shiori (Female)", false],
            ],
            "Javanese (Indonesia)" => [
                ["jv-ID-DimasNeural", "Dimas (Male)", false],
                ["jv-ID-SitiNeural", "Siti (Female)", false],
            ],
            "Kannada (India)" => [
                ["kn-IN-GaganNeural", "Gagan (Male)", false],
                ["kn-IN-SapnaNeural", "Sapna (Female)", false],
            ],
            "Kazakh (Kazakhstan)" => [
                ["kk-KZ-AigulNeural", "Aigul (Female)", false],
                ["kk-KZ-DauletNeural", "Daulet (Male)", false],
            ],
            "Khmer (Cambodia)" => [
                ["km-KH-PisethNeural", "Piseth (Male)", false],
                ["km-KH-SreymomNeural", "Sreymom (Female)", false],
            ],
            "Korean (Korea)" => [
                ["ko-KR-HyunsuNeural", "Hyunsu (Male)", false],
                ["ko-KR-InJoonNeural", "InJoon (Male)", false],
                ["ko-KR-SunHiNeural", "SunHi (Female)", false],
            ],
            "Lao (Laos)" => [
                ["lo-LA-ChanthavongNeural", "Chanthavong (Male)", false],
                ["lo-LA-KeomanyNeural", "Keomany (Female)", false],
            ],
            "Latvian (Latvia)" => [
                ["lv-LV-EveritaNeural", "Everita (Female)", false],
                ["lv-LV-NilsNeural", "Nils (Male)", false],
            ],
            "Lithuanian (Lithuania)" => [
                ["lt-LT-LeonasNeural", "Leonas (Male)", false],
                ["lt-LT-OnaNeural", "Ona (Female)", false],
            ],
            "Macedonian (North Macedonia)" => [
                ["mk-MK-AleksandarNeural", "Aleksandar (Male)", false],
                ["mk-MK-MarijaNeural", "Marija (Female)", false],
            ],
            "Malay (Malaysia)" => [
                ["ms-MY-OsmanNeural", "Osman (Male)", false],
                ["ms-MY-YasminNeural", "Yasmin (Female)", false],
            ],
            "Malayalam (India)" => [
                ["ml-IN-MidhunNeural", "Midhun (Male)", false],
                ["ml-IN-SobhanaNeural", "Sobhana (Female)", false],
            ],
            "Maltese (Malta)" => [
                ["mt-MT-GraceNeural", "Grace (Female)", false],
                ["mt-MT-JosephNeural", "Joseph (Male)", false],
            ],
            "Marathi (India)" => [
                ["mr-IN-AarohiNeural", "Aarohi (Female)", false],
                ["mr-IN-ManoharNeural", "Manohar (Male)", false],
            ],
            "Mongolian (Mongolia)" => [
                ["mn-MN-BataaNeural", "Bataa (Male)", false],
                ["mn-MN-YesuiNeural", "Yesui (Female)", false],
            ],
            "Nepali (Nepal)" => [
                ["ne-NP-HemkalaNeural", "Hemkala (Female)", false],
                ["ne-NP-SagarNeural", "Sagar (Male)", false],
            ],
            "Norwegian (Norway)" => [
                ["nb-NO-FinnNeural", "Finn (Male)", false],
                ["nb-NO-IselinNeural", "Iselin (Female)", false],
                ["nb-NO-PernilleNeural", "Pernille (Female)", false],
            ],
            "Pashto (Afghanistan)" => [
                ["ps-AF-GulNawazNeural", "GulNawaz (Male)", false],
                ["ps-AF-LatifaNeural", "Latifa (Female)", false],
            ],
            "Persian (Iran)" => [
                ["fa-IR-DilaraNeural", "Dilara (Female)", false],
                ["fa-IR-FaridNeural", "Farid (Male)", false],
            ],
            "Polish (Poland)" => [
                ["pl-PL-MarekNeural", "Marek (Male)", false],
                ["pl-PL-ZofiaNeural", "Zofia (Female)", false],
            ],
            "Portuguese (Brazil)" => [
                ["pt-BR-AntonioNeural", "Antonio (Male)", false],
                ["pt-BR-FranciscaNeural", "Francisca (Female)", false],
            ],
            "Portuguese (Portugal)" => [
                ["pt-PT-DuarteNeural", "Duarte (Male)", false],
                ["pt-PT-FernandaNeural", "Fernanda (Female)", false],
                ["pt-PT-RaquelNeural", "Raquel (Female)", false],
            ],
            "Romanian (Romania)" => [
                ["ro-RO-AlinaNeural", "Alina (Female)", false],
                ["ro-RO-EmilNeural", "Emil (Male)", false],
            ],
            "Russian (Russia)" => [
                ["ru-RU-DmitryNeural", "Dmitry (Male)", false],
                ["ru-RU-SvetlanaNeural", "Svetlana (Female)", false],
            ],
            "Serbian (Serbia)" => [
                ["sr-RS-NicholasNeural", "Nicholas (Male)", false],
                ["sr-RS-SophieNeural", "Sophie (Female)", false],
            ],
            "Sinhala (Sri Lanka)" => [
                ["si-LK-SameeraNeural", "Sameera (Male)", false],
                ["si-LK-ThiliniNeural", "Thilini (Female)", false],
            ],
            "Slovak (Slovakia)" => [
                ["sk-SK-LukasNeural", "Lukas (Male)", false],
                ["sk-SK-ViktoriaNeural", "Viktoria (Female)", false],
            ],
            "Slovenian (Slovenia)" => [
                ["sl-SI-PetraNeural", "Petra (Female)", false],
                ["sl-SI-RokNeural", "Rok (Male)", false],
            ],
            "Somali (Somalia)" => [
                ["so-SO-MuuseNeural", "Muuse (Male)", false],
                ["so-SO-UbaxNeural", "Ubax (Female)", false],
            ],
            "Spanish (Argentina)" => [
                ["es-AR-ElenaNeural", "Elena (Female)", false],
                ["es-AR-TomasNeural", "Tomas (Male)", false],
            ],
            "Spanish (Bolivia)" => [
                ["es-BO-MarceloNeural", "Marcelo (Male)", false],
                ["es-BO-SofiaNeural", "Sofia (Female)", false],
            ],
            "Spanish (Chile)" => [
                ["es-CL-CatalinaNeural", "Catalina (Female)", false],
                ["es-CL-LorenzoNeural", "Lorenzo (Male)", false],
            ],
            "Spanish (Colombia)" => [
                ["es-CO-GonzaloNeural", "Gonzalo (Male)", false],
                ["es-CO-SalomeNeural", "Salome (Female)", false],
            ],
            "Spanish (Costa Rica)" => [
                ["es-CR-JuanNeural", "Juan (Male)", false],
                ["es-CR-MariaNeural", "Maria (Female)", false],
            ],
            "Spanish (Cuba)" => [
                ["es-CU-BelkysNeural", "Belkys (Female)", false],
                ["es-CU-ManuelNeural", "Manuel (Male)", false],
            ],
            "Spanish (Dominican Republic)" => [
                ["es-DO-EmilioNeural", "Emilio (Male)", false],
                ["es-DO-RamonaNeural", "Ramona (Female)", false],
            ],
            "Spanish (Ecuador)" => [
                ["es-EC-AndreaNeural", "Andrea (Female)", false],
                ["es-EC-LuisNeural", "Luis (Male)", false],
            ],
            "Spanish (El Salvador)" => [
                ["es-SV-LorenaNeural", "Lorena (Female)", false],
                ["es-SV-RodrigoNeural", "Rodrigo (Male)", false],
            ],
            "Spanish (Equatorial Guinea)" => [
                ["es-GQ-JavierNeural", "Javier (Male)", false],
                ["es-GQ-TeresaNeural", "Teresa (Female)", false],
            ],
            "Spanish (Guatemala)" => [
                ["es-GT-AndresNeural", "Andres (Male)", false],
                ["es-GT-MartaNeural", "Marta (Female)", false],
            ],
            "Spanish (Honduras)" => [
                ["es-HN-CarlosNeural", "Carlos (Male)", false],
                ["es-HN-KarlaNeural", "Karla (Female)", false],
            ],
            "Spanish (Mexico)" => [
                ["es-MX-BeatrizNeural", "Beatriz (Female)", false],
                ["es-MX-CandelaNeural", "Candela (Female)", false],
                ["es-MX-CarlotaNeural", "Carlota (Female)", false],
                ["es-MX-DaliaNeural", "Dalia (Female)", false],
                ["es-MX-EldaNeural", "Elda (Female)", false],
                ["es-MX-GerardoNeural", "Gerardo (Male)", false],
                ["es-MX-JorgeNeural", "Jorge (Male)", false],
                ["es-MX-LarloNeural", "Larlo (Male)", false],
                ["es-MX-LibertoNeural", "Liberto (Male)", false],
                ["es-MX-LorenzoNeural", "Lorenzo (Male)", false],
                ["es-MX-MarinaNeural", "Marina (Female)", false],
                ["es-MX-NildaNeural", "Nilda (Female)", false],
                ["es-MX-PelayoNeural", "Pelayo (Male)", false],
                ["es-MX-RenataNeural", "Renata (Female)", false],
                ["es-MX-YagoNeural", "Yago (Male)", false],
            ],
            "Spanish (Nicaragua)" => [
                ["es-NI-FedericoNeural", "Federico (Male)", false],
                ["es-NI-YolandaNeural", "Yolanda (Female)", false],
            ],
            "Spanish (Panama)" => [
                ["es-PA-EmilioNeural", "Emilio (Male)", false],
                ["es-PA-MargaritaNeural", "Margarita (Female)", false],
            ],
            "Spanish (Paraguay)" => [
                ["es-PY-MarioNeural", "Mario (Male)", false],
                ["es-PY-TaniaNeural", "Tania (Female)", false],
            ],
            "Spanish (Peru)" => [
                ["es-PE-AlexNeural", "Alex (Male)", false],
                ["es-PE-CamilaNeural", "Camila (Female)", false],
            ],
            "Spanish (Puerto Rico)" => [
                ["es-PR-KarinaNeural", "Karina (Female)", false],
                ["es-PR-VictorNeural", "Victor (Male)", false],
            ],
            "Spanish (Spain)" => [
                ["es-ES-AbrilNeural", "Abril (Female)", false],
                ["es-ES-AlvaroNeural", "Alvaro (Male)", false],
                ["es-ES-ArnauNeural", "Arnau (Male)", false],
                ["es-ES-DarioNeural", "Dario (Male)", false],
                ["es-ES-ElviraNeural", "Elvira (Female)", false],
                ["es-ES-EliasNeural", "Elias (Male)", false],
                ["es-ES-EstherNeural", "Esther (Female)", false],
                ["es-ES-IreneNeural", "Irene (Female)", false],
                ["es-ES-LiaNeural", "Lia (Female)", false],
                ["es-ES-NilNeural", "Nil (Male)", false],
                ["es-ES-SaulNeural", "Saul (Male)", false],
                ["es-ES-TeoNeural", "Teo (Male)", false],
                ["es-ES-TrianaNeural", "Triana (Female)", false],
                ["es-ES-VeraNeural", "Vera (Female)", false],
            ],
            "Spanish (United States)" => [
                ["es-US-AlonsoNeural", "Alonso (Male)", false],
                ["es-US-PalomaNeural", "Paloma (Female)", false],
            ],
            "Spanish (Uruguay)" => [
                ["es-UY-MateoNeural", "Mateo (Male)", false],
                ["es-UY-ValentinaNeural", "Valentina (Female)", false],
            ],
            "Spanish (Venezuela)" => [
                ["es-VE-PaolaNeural", "Paola (Female)", false],
                ["es-VE-SebastianNeural", "Sebastian (Male)", false],
            ],
            "Sundanese (Indonesia)" => [
                ["su-ID-JajangNeural", "Jajang (Male)", false],
                ["su-ID-TutiNeural", "Tuti (Female)", false],
            ],
            "Swahili (Kenya)" => [
                ["sw-KE-RafikiNeural", "Rafiki (Male)", false],
                ["sw-KE-ZuriNeural", "Zuri (Female)", false],
            ],
            "Swahili (Tanzania)" => [
                ["sw-TZ-DaudiNeural", "Daudi (Male)", false],
                ["sw-TZ-RehemaNeural", "Rehema (Female)", false],
            ],
            "Swedish (Sweden)" => [
                ["sv-SE-MattiasNeural", "Mattias (Male)", false],
                ["sv-SE-SofieNeural", "Sofie (Female)", false],
            ],
            "Tamil (India)" => [
                ["ta-IN-PallaviNeural", "Pallavi (Female)", false],
                ["ta-IN-ValluvarNeural", "Valluvar (Male)", false],
            ],
            "Tamil (Malaysia)" => [
                ["ta-MY-KaniNeural", "Kani (Female)", false],
                ["ta-MY-SuryaNeural", "Surya (Male)", false],
            ],
            "Tamil (Singapore)" => [
                ["ta-SG-AnbuNeural", "Anbu (Male)", false],
                ["ta-SG-VenbaNeural", "Venba (Female)", false],
            ],
            "Tamil (Sri Lanka)" => [
                ["ta-LK-KumarNeural", "Kumar (Male)", false],
                ["ta-LK-SaranyaNeural", "Saranya (Female)", false],
            ],
            "Telugu (India)" => [
                ["te-IN-MohanNeural", "Mohan (Male)", false],
                ["te-IN-ShrutiNeural", "Shruti (Female)", false],
            ],
            "Thai (Thailand)" => [
                ["th-TH-AcharaNeural", "Achara (Female)", false],
                ["th-TH-NiwatNeural", "Niwat (Male)", false],
                ["th-TH-PremwadeeNeural", "Premwadee (Female)", false],
            ],
            "Turkish (Turkey)" => [
                ["tr-TR-AhmetNeural", "Ahmet (Male)", false],
                ["tr-TR-EmelNeural", "Emel (Female)", false],
            ],
            "Ukrainian (Ukraine)" => [
                ["uk-UA-OstapNeural", "Ostap (Male)", false],
                ["uk-UA-PolinaNeural", "Polina (Female)", false],
            ],
            "Urdu (India)" => [
                ["ur-IN-GulNeural", "Gul (Female)", false],
                ["ur-IN-SalmanNeural", "Salman (Male)", false],
            ],
            "Urdu (Pakistan)" => [
                ["ur-PK-AsadNeural", "Asad (Male)", false],
                ["ur-PK-UzmaNeural", "Uzma (Female)", false],
            ],
            "Uzbek (Uzbekistan)" => [
                ["uz-UZ-MadinaNeural", "Madina (Female)", false],
                ["uz-UZ-SardorNeural", "Sardor (Male)", false],
            ],
            "Vietnamese (Vietnam)" => [
                ["vi-VN-HoaiMyNeural", "HoaiMy (Female)", false],
                ["vi-VN-NamMinhNeural", "NamMinh (Male)", false],
            ],
            "Welsh (United Kingdom)" => [
                ["cy-GB-AledNeural", "Aled (Male)", false],
                ["cy-GB-NiaNeural", "Nia (Female)", false],
            ],
            "Zulu (South Africa)" => [
                ["zu-ZA-ThandoNeural", "Thando (Female)", false],
                ["zu-ZA-ThembaNeural", "Themba (Male)", false],
            ],
        ];
    }
}

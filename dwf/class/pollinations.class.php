<?php

/**
 * Cette classe permet d'utiliser des outils d'IA en passant par les endpoint de pollinations 
 *
 * @author LEGAGNEUR Matthieu <legagneur.matthieu@gmail.com>
 */
class pollinations {

    /**
     * Retourne une immage généré par IA (service : pollinations)
     * @param string $prompt Prompt de l'image
     * @param int $width Largeur de l'image (px)
     * @param int $height Hauteur de l'image (px)
     * @param int $seed seed de l'image (0 = random)
     * @param string $model Model générative, flux ou turbo (flux par défaut)
     * @return string URL de l'image
     */
    public static function image_src($prompt, $width = 1024, $height = 1024, $seed = 0, $model = "flux") {
        $prompt = urlencode($prompt);
        $seed = ($seed ? $seed : rand(1, 1000000));
        return"https://image.pollinations.ai/prompt/{$prompt}?width={$width}&height={$height}&seed={$seed}&model={$model}&private=true&nologo=true";
    }

    /**
     * Retourne un texte généré par IA (service : pollinations)
     * @param string $prompt Prompt du texte
     * @param string $system Prompt système pour guider l'ia
     * @param boolean $json retourne la reponse en json (false par defaut)
     * @param int $seed seed de l'image (0 = random)
     * @param string $model Model générative:
     * <ul>
         <li>openai (défaut)</li>
         <li>openai-large</li>
         <li>openai-reasoning</li>
         <li>qwen-coder</li>
         <li>llama</li>
         <li>llamascout</li>
         <li>mistral</li>
         <li>unity</li>
         <li>rtist</li>
         <li>searchgpt</li>
         <li>evil</li>
         <li>deepseek-reasoning</li>
         <li>deepseek-reasoning-large</li>
         <li>phi</li>
         <li>gemini</li>
         <li>hormoz</li>
         <li>deepseek</li>
         <li>sur</li>
     </ul>
     * @return string Texte généré
     */
    public static function text($prompt, $system = "", $json = false, $seed = 0, $model = "openai") {
        $prompt = urlencode($prompt);
        $system = urlencode($system);
        $json = ($json ? "true" : "false");
        $seed = ($seed ? $seed : time());
        return file_get_contents("https://text.pollinations.ai/{$prompt}?system={$system}&json={$json}&seed={$seed}&model={$model}&private=true");
    }
    
    /**
     * Retourne un audio généré par IA (service : pollinations/openai)
     * @param string $prompt Prompt de l'audio
     * @param string $voice Voix : alloy, echo, fable, onyx, nova, shimmer, coral, verse, ballad, ash, sage, amuch, dan
     * @return string Url de l'audio généré
     */
    public function audio_src($prompt,$voice="alloy") {
        $prompt = urlencode($prompt);
        return "https://text.pollinations.ai/{$prompt}?model=openai-audio&voice={$voice}";
    }
}

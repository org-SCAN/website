<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;

class Translation extends Model
{
    use HasFactory, Uuids, SoftDeletes;
    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['deleted_at', "created_at", "updated_at"];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Return ISO3 language name
     * @param $value
     * @return mixed
     */

    public function getLanguageAttribute($value)
    {
        return Language::find($value)->language;
    }

    /**
     * This function is used to translate with deepl
     */
    public function autoTranslate()
    {
        foreach (Language::otherLanguages() as $language) {
            // check that the API token and url are set and if not use the default translation
            try{
                $response = Http::withHeaders([
                    'Authorization' => "DeepL-Auth-Key " . env("TRANSLATION_API_TOKEN")
                ])->withBody(http_build_query([
                    //'source_lang' => Language::defaultLanguage()->API_language_key,
                    'target_lang' => $language->API_language_key,
                    'text' => utf8_encode($this->translation)
                ]), "application/x-www-form-urlencoded")->post(env("TRANSLATION_API_URL"));
                $content = $response->json()["translations"][0]["text"];
                Translation::handleTranslation(ListControl::find($this->list), $this->field_key, $content, $language->id);
            }
            catch(\Exception $e){
                // if the translation fails, use the default translation
               Translation::handleTranslation(ListControl::find($this->list), $this->field_key, $this->translation, $language->id);
            }
        }
    }

    public static function handleTranslation(ListControl $listControl, $element, $content, $language = "default")
    {
        $useAPI = $language == "default";
        $language = $language == "default" ? Language::defaultLanguage()->id : $language;

        $translation = Translation::whereLanguage($language)
            ->whereList($listControl->id)
            ->where('field_key', $element);
        if ($translation->exists()) {
            $translation = $translation->first();
            $useAPI = $useAPI && $translation->translation != $content;
            $translation->update(['translation' => $content]);
        } else {
            $translation = self::create([
                "language" => $language,
                "list" => $listControl->id,
                "field_key" => $element,
                "translation" => $content
            ]);
        }
        if ($useAPI) {
            $translation->autoTranslate();
        }
    }

}

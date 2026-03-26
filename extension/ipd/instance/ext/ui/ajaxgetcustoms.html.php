<?php
/**
 * The ajaxgetcustoms view file of instance module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     instance
 * @link        https://www.zentao.net
 */
namespace zin;

if($customFields)
{
    h::css(<<<CSS
.instance-custom-fields-block.hidden, #createStoreAppForm .form-row-group {display: block !important;}
.custom-field-label.required:before {
    content: "*";
    display: inline-block;
    margin-right: .25rem;
    --tw-translate-y: 0.125rem;
    --tw-scale-x: 1.25;
    --tw-scale-y: 1.25;
    transform: translate(var(--tw-translate-x),var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
    --tw-text-opacity: 1;
    color: rgba(var(--color-danger-500-rgb),var(--tw-text-opacity))
}
CSS
    );
    if(!$instanceID)
    {
        h::css('#customFieldsTable tr {border: none;} .custom-field-label {width: 140px; text-align: right; padding-right: 0.5rem !important;} .custom-field-value {padding: 0.5rem 0 !important;}');
    }

    $buildFormWg = function($field) use ($config)
    {
        if(!empty($field->autocomplete))
        {
            $ref = explode('.', $field->autocomplete);
            if(count($ref) == 2 && empty($field->value))
            {
                switch($field->autocomplete)
                {
                case 'zentao.http_protocol':
                    $field->value = strstr(getWebRoot(true), ':', true);
                    break;
                case 'zentao.http_host':
                    $field->value = trim(strstr(getWebRoot(true), ':', false), ':/');
                    break;
                case 'zentao.webroot':
                    $field->value = getWebRoot();
                    break;
                case 'zentao.xxd_token':
                    $field->value = zget($config->xuanxuan, 'key', '');
                    break;
                default:
                    break;
                }
            }
        }

        if($field->type == 'switch') $field->type = 'switcher';
        $isInput  = in_array($field->type, array('text', 'password', 'number'));
        $isSelect = in_array($field->type, array('select', 'radio', 'checkbox'));
        if($isSelect)
        {
            if(!empty($field->options_api)) $field->options = $this->instance->getOptionsByApi($field->options_api);
            $field->options = array_column($field->options, 'text', 'value');

            if($field->type == 'radio')    $field->type = 'radioList';
            if($field->type == 'select')   $field->type = 'picker';
            if($field->type == 'checkbox') $field->type = 'checkList';
        }

        $value = empty($field->value) ? $field->default : $field->value;
        if($field->type == 'switcher') $value = 1;
        if($field->type == 'checkList' || $field->style == 'multi') $value = is_array($value) ? $value : explode(zget($field, 'separator', ','), (string)$value);
        return control(setClass('custom-field'), set::type($field->type), set::name($field->name . ($field->type == 'checkList' ? '[]' : '')), set::value($value), set::placeholder($field->desc), set::required($field->required), $field->type == 'switcher' && ($field->default || !empty($field->value)) ? set::checked((bool)zget($field, 'value', $field->default)) : null, $isInput ? set::placeholder($field->desc)    : null, $isInput ? set::autocomplete('new-password') : null, $isInput ? set::maxlength(zget($field, 'length', 255)) : null, $field->type == 'number' ? set::min(zget($field, 'min', 0))   : null, $field->type == 'number' ? set::min(zget($field, 'min', 0))   : null, $field->type == 'number' ? set::step(zget($field, 'step', 1)) : null, $isSelect ? set::items($field->options) : null, strpos($field->type, 'List') ? set::inline($field->style == 'inline')  : null, $field->type == 'picker'     ? set::multiple($field->style == 'multi') : null);

    };

    $dependFields = array();
    foreach($customFields as $field)
    {
        if(!empty($field->depends))
        {
            foreach($field->depends as $dependField) $dependFields[$dependField->key][] = $field->name;
        }

        if(!hasPriv('instance', 'manage') && $field->type == 'password') $field->value = preg_replace('/./', '*', $field->value);

        $fields[] = h::tr
        (
            setClass("{$field->name}-row-tr", empty($field->depends) ? null : 'hidden'),
            h::td
            (
                setClass('custom-field-label', !empty($field->required) ? 'required' : null),
                zget($lang->instance, $field->name, $field->label)
            ),
            h::td
            (
                setClass('custom-field-value', in_array($field->type, array('text', 'password', 'number')) ? 'input-box' : null),
                hasPriv('instance', 'manage') ? $buildFormWg($field) : $field->value
            ),
            $instanceID ? h::td($field->desc) : null
        );
    }

    jsVar('dependFields', $dependFields);
    jsVar('customFields', array_column($customFields, null, 'name'));

    h::table
    (
        setID('customFieldsTable'),
        setClass('table w-full max-w-full', $instanceID ? 'bordered mt-4' : null),
        on::input('#customFieldsTable input')->call('window.setCustomField', jsRaw('this')),
        on::change('#customFieldsTable .picker-box input, #customFieldsTable select')->call('window.setCustomField', jsRaw('this')),
        $instanceID ? h::tr
        (
            h::th(setClass('w-1/3'), $lang->instance->custom->name),
            h::th($lang->instance->custom->value),
            h::th(setClass('w-1/3'), $lang->instance->custom->desc)
        ) : null,
        $fields,
        $instanceID && hasPriv('instance', 'manage') ? h::tr
        (
            h::td(
                set::colspan(3),
                setClass('no-label text-center'),
                btn
                (
                    setClass('primary custom-btn disabled'),
                    $lang->save
                )
            )
        ) : null
    );
}

$(function()
{
    if(typeof(reviewedPoints) != 'undefined')
    {
        window.waitDom('[name=object]', function(){
            $picker  = $('[name=object]').zui('picker');
            $options = $picker.options;

            let $items   = [{text: '', value: '', key: ''}];
            for(item in $options.items)
            {
              let $value    = $options.items[item].value;
              let $text     = $options.items[item].text;
              let $key      = $options.items[item].key;
              let $disabled = false;

              if(typeof reviewedPoints[$value] !== "undefined" && reviewedPoints[$value].disabled) $disabled = true;

              $items.push({text: $text, value: $value, disabled: $disabled, key: $key});
            }

            $options.items = $items;
            $picker.render($options);
      });
   }
})

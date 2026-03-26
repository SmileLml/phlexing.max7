$(function()
{
    $(document).on('click', '#relationTable .addRelation', function()
    {
        var $tr = $(this).parents('tr');
        $tr.after(window.itemRow.replace(/KEY/g, window.key));
        $tr.next().find('select').chosen();

        processNext();

        window.key++;
    });

    $(document).on('click', '#relationTable .delRelation', function()
    {
        var $tr = $(this).parents('tr');
        if($('#relationTable .delRelation').length == 1)
        {
            $tr.find('select').val('').trigger('chosen:updated');
            $tr.find('input[type=checkbox]').prop('checked', false);
            return false;
        }

        $tr.remove();

        processNext();
    });

    $(document).on('change', '#relationTable [name^=next]', function()
    {
        processNext();

        var $field = $(this).parents('tr').find('[name^=field]');
        var field  = $field.val();
        var link   = createLink('workflowfield', 'ajaxGetField', 'module=' + $(this).val());
        $field.load(link, function(response)
        {
            $field.val(field).trigger('chosen:updated');
        });
    });
    
    /* Relation */
    $(document).on('change', '#relationTable [name^=createField]', function()
    {
        var $parent = $(this).parents('.input-group');
        var checked = $(this).prop('checked');

        $parent.find('[name^=field]').val('').trigger('chosen:updated');
        $parent.find('[name^=newField]').toggle(checked).val('');
        $parent.find('.chosen-container').toggle(!checked);
    });

    $(document).on('change', '#relationTable [name^=action]', function()
    {
        var value   = $(this).val();
        var checked = $(this).prop('checked');

        if(checked)
        {
            if(value == 'one2one')   $(this).parents('td').find('[value=one2many]').prop('checked', false);
            if(value == 'one2many')  $(this).parents('td').find('[value=one2one]').prop('checked', false);
            if(value == 'many2one')  $(this).parents('td').find('[value=many2many]').prop('checked', false);
            if(value == 'many2many') $(this).parents('td').find('[value=many2one]').prop('checked', false);
        }
    });

    $('#relationTable [name^=next]').change();
})

/**
 * Make sure each next module and field can be selected only once.
 *
 * @access public
 * @return void
 */
function processNext()
{
    $('#relationTable [name^=next]').each(function()
    {
        var $this    = $(this);
        var selected = $this.val();
        $this.empty().append($('#relationTemplateNext').html());
        $('#relationTable [name^=next]').not(this).each(function()
        {
            var next = $(this).val();
            if(next != 0) $this.find('option[value=' + next + ']').remove();
        });
        $this.val(selected).trigger('chosen:updated');
    });
}

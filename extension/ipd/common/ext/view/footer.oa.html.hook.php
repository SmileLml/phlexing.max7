<?php
$this->loadModel('attend');
$isLogon = $this->loadModel('user')->isLogon();
if($isLogon and empty($this->app->user->signed) and $this->attend->checkSignIn())
{
    $_SESSION['user']->signed = true;
    $this->app->user->signed  = true;
}
if($isLogon and isset($_SESSION['user']) and (!isset($this->app->user->mustSignOut) or $this->app->user->mustSignOut != $this->config->attend->mustSignOut))
{
    $_SESSION['user']->mustSignOut = $this->config->attend->mustSignOut;
    $this->app->user->mustSignOut  = $this->config->attend->mustSignOut;
}
?>
<?php if($isLogon and empty($this->app->user->signed)) $this->attend->signIn($this->app->user->account);?>
<script>
<?php if($isLogon and !empty($this->app->user->signed) and $this->app->user->mustSignOut == 'no'):?>
$.get(createLink('attend', 'signOut'));
<?php endif;?>
<?php if($isLogon and $this->app->user->mustSignOut == 'yes'):?>
$('#poweredBy').append(<?php echo json_encode(html::a($this->createLink('user', 'logout'), $lang->logout, '', "class='btn btn-sm btn-danger' id='signOut' style='color:#fff;'"));?>)
<?php if(strpos(",{$this->config->attend->noAttendUsers},", ",{$this->app->user->account},") === false and $this->config->attend->signOutLimit):?>
if(new Date().getTime() < '<?php echo strtotime(date("Y-m-d") . ' ' . $this->config->attend->signOutLimit) * 1000;?>')
{
    $('#poweredBy #signOut').click(function()
    {
        if(!confirm(<?php echo json_encode($lang->attend->confirmEarly);?>)) return false;
    })
}
<?php endif;?>
<?php endif;?>

<?php $moduleName = $this->app->getModuleName();?>
<?php if(isset($lang->oa->menu->$moduleName)):?>
$('#footer #crumbs').append("<i class='icon-angle-right'></i> <?php echo $title?>");
<?php endif;?>
$.extend(
{
    setAjaxForm: function(formID, callback, beforeSubmit)
    {
        if($(document).data('setAjaxForm:' + formID)) return;

        form = $(formID);

        var options =
        {
            target  : null,
            timeout : config.timeout,
            dataType:'json',

            success: function(response)
            {
                var submitButton = $(formID).find(':input[type=submit], .submit');

                /* The response is not an object, some error occers, bootbox.alert it. */
                if($.type(response) != 'object')
                {
                    $.enableForm(formID);
                    if(response) return bootbox.alert(response);
                    return bootbox.alert('No response.');
                }

                /* The response.result is success. */
                if(response.result == 'success')
                {
                    if(response.message && response.message.length)
                    {
                        submitButton.popover($.extend(
                        {
                            trigger: 'manual',
                            content: response.message,
                            tipClass: 'popover-success popover-form-result',
                            placement: response.placement ? response.placement : 'right'
                        }, submitButton.data())).popover('show');
                        setTimeout(function(){submitButton.popover('destroy')}, 2000);
                    }

                    if($.isFunction(callback)) return callback(response);

                    if($('#responser').length && response.message && response.message.length)
                    {
                        $('#responser').html(response.message).addClass('red f-12px').show().delay(3000).fadeOut(100);
                    }

                    if(response.closeModal)
                    {
                        setTimeout($.zui.closeModal, 1200);
                    }

                    if(response.callback)
                    {
                        var rcall = window[response.callback];
                        if($.isFunction(rcall))
                        {
                            if(rcall() === false)
                            {
                                return;
                            }
                        }
                    }

                    if(response.locate)
                    {
                        if(response.locate == 'loadInModal')
                        {
                            var modal = $('.modal');
                            setTimeout(function()
                            {
                                modal.load(modal.attr('ref'), function(){$(this).find('.modal-dialog').css('width', $(this).data('width'));
                                $.zui.ajustModalPosition()})
                            }, 1000);
                        }
                        else if(response.locate == 'parent')
                        {
                            setTimeout(function(){window.parent.location.reload();}, 1200);
                        }
                        else
                        {
                            var reloadUrl = response.locate == 'reload' ? location.href : response.locate;
                            setTimeout(function(){location.href = reloadUrl;}, 1200);
                        }
                    }

                    if(response.ajaxReload)
                    {
                        var $target = $(response.ajaxReload);
                        if($target.length === 1)
                        {
                            $target.load(document.location.href + ' ' + response.ajaxReload, function()
                            {
                                // $target.dataTable();
                                $target.find('[data-toggle="modal"]').modalTrigger();
                            });
                        }
                    }

                    return true;
                }

                /**
                 * The response.result is fail.
                 */

                $.enableForm(formID);
                /* The result.message is just a string. */
                if($.type(response.message) == 'string')
                {
                    if($('#responser').length == 0)
                    {
                        submitButton.popover($.extend(
                        {
                            trigger: 'manual',
                            content: response.message,
                            tipClass: 'popover-danger popover-form-result',
                            placement: response.placement ? response.placement : 'right'
                        }, submitButton.data())).popover('show');
                        setTimeout(function(){submitButton.popover('destroy')}, 2000);
                    }
                    $('#responser').html(response.message).addClass('red small text-danger').show().delay(5000).fadeOut(100);
                }

                /* The result.message is just a object. */
                if($.type(response.message) == 'object')
                {
                    $.each(response.message, function(key, value)
                    {
                        /* Define the id of the error objecjt and it's label. */
                        var errorOBJ   = '#' + key;
                        var errorLabel = key + 'Label';

                        /* Create the error message. */
                        var errorMSG = '<span id="' + errorLabel + '" for="' + key  + '"  class="text-error red">';
                        if($.type(value) == 'string')
                        {
                            errorMSG += value;
                        }
                        else
                        {
                            $.each(value, function(subKey, subValue)
                            {
                                errorMSG += subKey != value.length - 1 ? subValue.replace(/[\。|\.]/, ';') : subValue;
                            })
                        }
                        errorMSG += '</span>';

                        /* Append error message, set style and set the focus events. */
                        $('#' + errorLabel).remove();
                        var $errorOBJ = $(formID).find(errorOBJ);
                        if($errorOBJ.closest('.input-group').length > 0)
                        {
                            $errorOBJ.closest('.input-group').after(errorMSG)
                        }
                        else
                        {
                            $errorOBJ.parent().append(errorMSG);
                        }
                        $errorOBJ.css('margin-bottom', 0);
                        $errorOBJ.css('border-color','#953B39')
                        $errorOBJ.change(function()
                        {
                            $errorOBJ.css('margin-bottom', 0);
                            $errorOBJ.css('border-color','')
                            $('#' + errorLabel).remove();
                        });
                    })

                    /* Focus the first error field thus to nitify the user. */
                    var firstErrorField = $('#' +$('span.red').first().attr('for'));
                    var topOffset;
                    if(firstErrorField.length) topOffset = parseInt(firstErrorField.offset().top) - 20;   // 20px offset more for margin.

                    /* If there's the navbar-fixed-top element, minus it's height. */
                    if($('.navbar-fixed-top').size())
                    {
                        topOffset = topOffset - parseInt($('.navbar-fixed-top').height());
                    }

                    /* Scroll to the error field and foucus it. */
                    $(document).scrollTop(topOffset);
                    firstErrorField.focus();
                }

                if($.isFunction(callback)) return callback(response);
            },

            /* When error occers, alert the response text, status and error. */
            error: function(jqXHR, textStatus, errorThrown)
            {
                $.enableForm(formID);
                if(textStatus == 'timeout')
                {
                    bootbox.alert(v.lang.timeout);
                    return false;
                }
                bootbox.alert(jqXHR.responseText + textStatus + errorThrown);
            }
        };

        /* Call ajaxSubmit to sumit the form. */
        $(document).on('submit', formID, function()
        {
            $.disableForm(formID);
            if(!beforeSubmit || beforeSubmit() !== false)
            {
                $(this).ajaxSubmit(options);
            }
            else
            {
                $.enableForm(formID);
            }
            return false;    // Prevent the submitting event of the browser.
        }).data('setAjaxForm:' + formID, true);
    },

    /* Disable a form. */
    disableForm:function(formID)
    {
        $(formID).find(':submit').attr('disabled', true);
    },

    /**
     * Set ajax loader.
     *
     * Bind click event for some elements thus when click them,
     * use $.load to load page into target.
     *
     * @param string selector
     * @param string target
     */
    setAjaxLoader: function(selector, target)
    {
        /* Avoid duplication of binding */
        var data = $('body').data('ajaxLoader');
        if(data && data[selector]) return;
        if(!data) data = {};
        data[selector] = true;
        $('body').data('ajaxLoader', data);

        $(document).on('click', selector, function()
        {
            var url = $(this).attr('href');
            if(!url) url = $(this).data('rel');
            if(!url) return false;

            var $target = $(target);
            if(!$target.size()) return false;

            var width = $(this).data('width');
            $target.load(url, function()
            {
                if(width) $target.find('.modal-dialog').css('width', width);
                if($target.hasClass('modal') && $.zui.ajustModalPosition)
                {
                    $.zui.ajustModalPosition();
                    $target.find('.modal-dialog .modal-body').resize(function(){$.zui.ajustModalPosition();});
                }
            });

            return false;
        });
    },

    /**
     * Set ajax jsoner.
     *
     * @param string   selector
     * @param object   callback
     */
    setAjaxJSONER: function(selector, callback)
    {
        $(document).on('click', selector, function()
        {
            /* Try to get the href of current element, then try it's data-rel attribute. */
            url = $(this).attr('href');
            if(!url) url = $(this).data('rel');
            if(!url) return false;

            $.getJSON(url, function(response)
            {
                /* If set callback, call it. */
                if($.isFunction(callback)) return callback(response);

                /* If the response has message attribute, show it in #responser or alert it. */
                if(response.message)
                {
                    if($('#responser').length)
                    {
                        $('#responser').html(response.message);
                        $('#responser').addClass('text-info f-12px');
                        $('#responser').show().delay(3000).fadeOut(100);
                    }
                    else
                    {
                        bootbox.alert(response.message);
                    }
                }

                /* If the response has locate param, locate the browse. */
                if(response.locate) return location.href = response.locate;

                /* If target and source returned in reponse, update target with the source. */
                if(response.target && response.source)
                {
                    $(response.target).load(response.source);
                }
            });

            return false;
        });
    },

    /**
     * Set ajax deleter.
     *
     * @param  string $selector
     * @access public
     * @return void
     */
    setAjaxDeleter: function (selector, callback)
    {
        $(selector).each(function()
        {
            href = $(this).attr('href');
            $(this).attr('href', '###').attr('data-href', href);
        })

        $(document).on('click', selector, function()
        {
            if(confirm('<?php echo $lang->confirmDelete?>'))
            {
                var deleter = $(this);
                deleter.text('<?php echo $lang->deleteing?>');

                $.getJSON(deleter.attr('data-href'), function(data)
                {
                    callback && callback(data);
                    if(data.result == 'success')
                    {
                        if(deleter.parents('#ajaxModal').size()) return $.reloadAjaxModal(1200);
                        if(data.locate) return location.href = data.locate;
                        return location.reload();
                    }
                    else
                    {
                        alert(data.message);
                        return location.reload();
                    }
                });
            }
            return false;
        });
        return false;
    },

    /**
     * Set reload deleter.
     *
     * @param  string $selector
     * @access public
     * @return void
     */
    setReloadDeleter: function (selector)
    {
        href = $(selector).attr('href');
        $(selector).attr('href', '###').attr('data-href', href);

        $(document).on('click', selector, function()
        {
            if(confirm('<?php echo $lang->confirmDelete?>'))
            {
                var deleter = $(this);
                deleter.text('<?php echo $lang->deleteing?>');

                $.getJSON(deleter.attr('data-href'), function(data)
                {
                    var afterDelete = deleter.data('afterDelete');
                    if($.isFunction(afterDelete))
                    {
                        $.proxy(afterDelete, deleter)(data);
                    }

                    if(data.result == 'success')
                    {
                        var table     = $(deleter).closest('table');
                        var replaceID = table.attr('id');

                        table.wrap("<div id='tmpDiv'></div>");
                        var $tmpDiv = $('#tmpDiv');
                        $tmpDiv.load(document.location.href + ' #' + replaceID, function()
                        {
                            $tmpDiv.replaceWith($tmpDiv.html());
                            var $target = $('#' + replaceID);
                            $target.find('.reloadDeleter').data('afterDelete', afterDelete);
                            $target.find('[data-toggle="modal"]').modalTrigger();
                            if($target.hasClass('table-data'))
                            {
                                $target.dataTable();
                            }
                            if(typeof sortTable == 'function')
                            {
                                sortTable();
                            }
                            else
                            {
                                $('tfoot td').css('background', 'white').unbind('click').unbind('hover');
                            }
                        });
                    }
                    else
                    {
                        alert(data.message);
                    }
                });
            }
            return false;
        });
    },

    /**
     * Set reload.
     *
     * @param  string $selector
     * @access public
     * @return void
     */
    setReload: function (selector)
    {
        $(document).on('click', selector, function()
        {
            var reload = $(this);
            $.getJSON(reload.attr('href'), function(data)
            {
                if(data.result == 'success')
                {
                    var table     = $(reload).closest('table');
                    var replaceID = table.attr('id');

                    table.wrap("<div id='tmpDiv'></div>");
                    $('#tmpDiv').load(document.location.href + ' #' + replaceID, function()
                    {
                        $('#tmpDiv').replaceWith($('#tmpDiv').html());
                        if(typeof sortTable == 'function')
                        {
                            sortTable();
                        }
                        else
                        {
                            $('tfoot td').css('background', 'white').unbind('click').unbind('hover');
                        }
                    });
                }
                else
                {
                    alert(data.message);
                }
            });
            return false;
        });
    },

    /**
     * Reload ajax modal.
     *
     * @param int duration
     * @access public
     * @return void
     */
    reloadAjaxModal: function(duration)
    {
        if(typeof(duration) == 'undefined') duration = 1000;
        setTimeout(function()
        {
            var modal = $('#ajaxModal');
            modal.load(modal.attr('ref'), function(){$(this).find('.modal-dialog').css('width', $(this).data('width')); $.zui.ajustModalPosition()})
        }, duration);
    }
});

$(function()
{
    $.setAjaxDeleter('.deleter');
    $.setAjaxLoader('.loadInModal', '#triggerModal');
    $('[data-toggle="tooltip"]').tooltip();
    $.ajaxForm('#ajaxForm');
})

function setRequiredFields()
{
    if(!config.requiredFields) return false;
    requiredFields = config.requiredFields.split(',');
    for(i = 0; i < requiredFields.length; i++)
    {
        $('#' + requiredFields[i]).closest('td,th').prepend("<div class='required required-wrapper'></div>");
        var colEle = $('#' + requiredFields[i]).closest('[class*="col-"]');
        if(colEle.parent().hasClass('form-group')) colEle.addClass('required');
    }
}

/**
 * The most recent month is highlighted
 */
function addRecentlyMonthActive()
{
    var isActive = false;
    $('.side .panel-body .tree ul').each(function()
    {
        $(this).find('li').each(function()
        {
            if($(this).hasClass('active') && !isActive) isActive = true;
        })
	});

    if(!isActive) $('.side .panel-body .tree ul:last li:last').addClass('active')
}
</script>

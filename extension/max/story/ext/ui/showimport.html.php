<?php
namespace zin;

jsVar('forceReview', data('forceReview'));
jsVar('storyType', data('type'));
if($app->tab == 'product') data('activeMenuID', $type);
jsVar('productID',     data('productID'));
jsVar('hasBranch',     data('hasBranch'));
jsVar('branchModules', data('branchModules'));
jsVar('branchPlans',   data('branchPlans'));
jsVar('allModules',    data('stories.fields.module.items'));
jsVar('allPlans',      data('stories.fields.plan.items'));
include $app->getModuleRoot() . 'transfer/ui/showimport.html.php';

pageJS(<<<'JS'
$(document).off('change', "[name^='branch']").on('change', "[name^='branch']", function(e)
{
    setModuleAndPlanByBranch(e);
});

window.renderImportRowData = function($row, index, row)
{
    if(hasBranch === true && typeof row.branch != 'undefined' && row.branch !== '')
    {
        const modules = typeof branchModules[row.branch] == 'undefined' ? allModules : branchModules[row.branch];
        $row.find('.form-batch-control[data-name="module"]').find('.picker-box').on('inited', function(e, info)
        {
            let $picker = info[0];
            let options = $picker.options;
            options.items = modules != null ? modules : [];
            options.defaultValue = row.module;
            $picker.render(options);
            $picker.$.setValue(row.module);
        });

        const plans = typeof branchPlans[row.branch] == 'undefined' ? allPlans : branchPlans[row.branch];
        $row.find('.form-batch-control[data-name="plan"]').find('.picker-box').on('inited', function(e, info)
        {
            let $picker = info[0];
            let options = $picker.options;
            options.items = plans != null ? plans : [];
            options.defaultValue = row.plan;
            $picker.render(options);
            $picker.$.setValue(row.plan);
        });
    }
};
JS
);

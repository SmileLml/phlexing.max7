let nodes = '';
window.clickSubmit = function()
{
    nodes = $('[name=nodes]').val();
    nodes = JSON.parse(nodes);
    if(nodes.length <= 2)
    {
        zui.Messager.show({content: warningLang['needReview'], close: false});
        return false;
    }
}


window.genID = function()
{
    return Math.random().toString(36).substr(2);
}

window.clickConditionNode = function(event)
{
    var $pa = $(event.target).closest('.editor-node').parent().closest('.editor-node');
    var id  = $pa.data('id');
    getNode(id, function(index, father, index2, grandpa)
    {
        if(index == 'default') return;

        var selectedFather = father.branches;
        var node  = selectedFather[index];
        node.type = 'conditions';

        $.post(link, {nodes: JSON.stringify(nodes), 'type': 'update', 'node': JSON.stringify(node)}, function(data)
        {
            $('#modal').html($(data).find('#modal').html()).zuiInit();
            zui.Modal.open({id: 'modal'});
        });
    });
}

window.clickNode = function(event)
{
    var $pa = $(event.target).closest('.editor-node');
    var id  = $pa.data('id');
    getNode(id, function(index, father, index2, grandpa)
    {
        if(index == 'default') return;

        var node = father[index];
        node.nodeIndex = index2;

        $.post(link, {nodes: JSON.stringify(nodes), 'type': 'update', 'node': JSON.stringify(node)}, function(data)
        {
            $('#modal').html($(data).find('#modal').html()).zuiInit();
            zui.Modal.open({id: 'modal', size: 'lg'});
        });
    });
}

window.hideAddType = function()
{
    $('#editor .add-node-types').remove();
    $('#editor .add-node-btn-active').removeClass('add-node-btn-active');
}

window.addNodeType = function(event)
{
    hideAddType();

    const addText = $('#addNode').html();
    $obj = $(event.target).closest('.add-node-btn');
    $obj.addClass('add-node-btn-active');
    $obj.append(addText);

    return false;
}

window.addNode = function(event)
{
    let $pa           = $(event.target).closest('.editor-node');
    const type        = $(event.target).closest('.node-type').data('type');
    const isCondition = $pa.hasClass('condition');
    if(isCondition && type == 'reviewer') $pa = $pa.parent().closest('.editor-node');
    if(isCondition && type != 'reviewer') $pa = $pa.next('.editor-node');

    const id = $pa.data('id');
    getNode(id, function(index, father, index2, grandpa)
    {
        index = isCondition ? index : parseInt(index) + 1;
        if(isCondition && type == 'reviewer')
        {
            father = (index == 'default') ? father.default.nodes : father.branches[index].nodes;
            index = 0;
        }

        let node = {};
        if(type == 'reviewer') node = {id: genID(), title: nodeTypeLang['approval'], reviewType: 'manual', type: 'approval', reviewers: [{type: 'select'}], agentType: 'pass', ccs: []};
        if(type == 'cc')       node = {id: genID(), title: nodeTypeLang['cc'], type: 'cc', ccs: [{type: 'select'}]};
        if(type == 'condition' || type == 'parallel')
        {
            node = {
                id: genID(),
                type: 'branch',
                branchType: type,
                branches: [{
                    id: genID(),
                    conditions: [],
                    nodes:[{id: genID(), type: 'approval', reviewers: [{type: 'select'}]}]
                }],
                default: {
                    id: genID(),
                    nodes: [{id: genID(), type: 'approval', reviewers: [{type: 'select'}]}]
                }
            };
        }
        father.splice(index, 0, node);
        $.post(link, {nodes: JSON.stringify(nodes), 'type': 'update'}, function(data)
        {
            $('#editor').html($(data).find('#editor').html()).zuiInit();
        });
    })
}

window.getNode = function(nodeID, callback)
{
    nodes = $('[name=nodes]').val();
    nodes = JSON.parse(nodes);
    if(nodeID == 'start')
    {
        callback(0, nodes);
        return [0];
    }
    else if(nodeID == 'end')
    {
        callback(nodes.length-1, nodes);
        return [nodes.length-1];
    }
    else
    {
        return findFromNodes(nodeID, nodes, callback);
    }
}

window.findFromNodes = function(nodeID, nodes, callback, parentIndex)
{
    for(let index in nodes)
    {
        var node = nodes[index];
        if(node.id == nodeID)
        {
            callback(index, nodes, parentIndex ? (parseInt(parentIndex) + parseInt(index)) : index);
            return [index];
        }

        if(node.type == 'branch')
        {
            for(var index2 in node.branches)
            {
                var path   = [index, 'branches', index2];
                var branch = node.branches[index2];

                if(branch.id == nodeID)
                {
                    callback(index2, node, index, nodes);
                    return path;
                }

                var result = findFromNodes(nodeID, branch.nodes, callback, index);
                if(result.length > 0)
                {
                    path.push('nodes');
                    return path.concat(result);
                }
            }

            // default
            var path = [index, 'default'];
            var branch = node.default;
            if(branch.id == nodeID)
            {
                callback('default', node, index, nodes);
                return path;
            }

            var result = findFromNodes(nodeID, branch.nodes, callback, index);
            if(result.length > 0) return path.concat(result);
        }
   }

   return [];
}

window.deleteNode = function(event)
{
    var id = $(event.target).closest('.editor-node').data('id');
    getNode(id, function(index, father)
    {
        father.splice(index,1)
        $.post(link, {nodes: JSON.stringify(nodes), 'type': 'update'}, function(data)
        {
            $('#editor').html($(data).find('#editor').html()).zuiInit();
        });
    })
};

window.deleteBranch = function(event)
{
    var id = $(event.target).closest('.branch').data('id');
    getNode(id, function(index, father, index2, grandpa)
    {
        father.branches.splice(index, 1);
        if(father.branches.length == 0) grandpa.splice(index2, 1)

        $.post(link, {nodes: JSON.stringify(nodes), 'type': 'update'}, function(data)
        {
            $('#editor').html($(data).find('#editor').html()).zuiInit();
        });
    })
};

window.addCondition = function(event)
{
    var id = $(event.target).closest('.editor-node').data('id');
    getNode(id, function(index, father)
    {
        father[index].branches.push({
            id: genID(),
            conditions: [],
            nodes: [{id: genID(), reviewType: 'manual', type: 'approval', reviewers: [{type: 'select'}]}]
        });

        $.post(link, {nodes: JSON.stringify(nodes), 'type': 'update'}, function(data)
        {
            $('#editor').html($(data).find('#editor').html()).zuiInit();
        });
    });
}

window.saveCondition = function(event)
{
    const id       = $(event.target).closest('button').data('id');
    const formData = new FormData($(event.target).closest('form').eq(0)[0]);
    getNode(id, function(index1, father, index2, grandpa)
    {
        var conditions = {};
        for(var d of formData)
        {
            var index = parseInt(d[0].match(/\d+/)[0]);
            var type  = d[0].match(/[a-zA-Z]+/)[0];

            if(index == 1 && type == 'conditionLogical') continue;

            conditions[index] = conditions[index] || {};
            conditions[index][type] = d[1];
        }

        var message = checkConditions(conditions);
        if(message)
        {
            zui.Messager.show({content:message, close: false});
            return;
        }

        var arrayConditions = [];
        for(var i in conditions) arrayConditions.push(conditions[i]);
        father.branches[index1].conditions = arrayConditions;

        $.post(link, {nodes: JSON.stringify(nodes), 'type': 'update'}, function(data)
        {
            $('body').data('zui.Modal').hide();
            $('#editor').html($(data).find('#editor').html()).zuiInit();
        });
    })
}

window.checkConditions = function(conditions)
{
    for(var i in conditions)
    {
        var condition = conditions[i];
        if(!condition.conditionValue) return warningLang['needValue'];
    }

    return '';
}

window.saveNode = function(event)
{
    var   data     = {};
    const id       = $(event.target).closest('button').data('id');
    const formData = new FormData($(event.target).closest('form').eq(0)[0]);
    getNode(id, function(index1, father, index2, grandpa)
    {
        var ccs       = {};
        var reviewers = {};
        var node      = father[index1];
        if(node.type != 'start' && node.type != 'end') data.id = node.id;
        data.type = node.type;

        for(var d of formData)
        {
            if(d[0] == 'title')
            {
                if(node.type == 'approval' || node.type == 'cc') data.title = d[1]; // 审批和抄送可以设置标题
            }
            else if (node.type == 'approval')  // 只有审批节点可以设置审批人
            {
                if(d[0] == 'reviewType')
                {
                    data.reviewType = d[1];
                    if(d[1] != 'manual') continue;
                }
                if(data.reviewType == 'manual')
                {
                    if(d[0].indexOf('type') === 0)
                    {
                        var index = parseInt(d[0].substring(5));
                        reviewers[index] = {type: d[1]};
                        if(d[1] == 'select')
                        {
                            reviewers[index].users     = [];
                            reviewers[index].roles     = [];
                            reviewers[index].depts     = [];
                            reviewers[index].positions = [];
                        }
                        if(d[1] == 'appointee')     reviewers[index].users          = [];
                        if(d[1] == 'role')          reviewers[index].roles          = [];
                        if(d[1] == 'position')      reviewers[index].positions      = [];
                        if(d[1] == 'productRole')   reviewers[index].productRoles   = [];
                        if(d[1] == 'projectRole')   reviewers[index].projectRoles   = [];
                        if(d[1] == 'superiorList')  reviewers[index].superiorList   = '';
                        if(d[1] == 'executionRole') reviewers[index].executionRoles = [];
                        if(d[1] == 'required')      reviewers[index].required       = '';

                    }
                    else if(d[0].indexOf('roles') === 0)
                    {
                        var index = parseInt(d[0].substring(6));
                        if(reviewers[index].type == 'role') reviewers[index].roles.push(d[1]);  // 角色
                    }
                    else if(d[0].indexOf('positions') === 0)
                    {
                        var index = parseInt(d[0].substring(10));
                        if(reviewers[index].type == 'position') reviewers[index].positions.push(d[1]); // 职位
                    }
                    else if(d[0].indexOf('productRoles') === 0)
                    {
                        var index = parseInt(d[0].substring(13));
                        if(reviewers[index].type == 'productRole') reviewers[index].productRoles.push(d[1]); // 产品角色
                    }
                    else if(d[0].indexOf('projectRoles') === 0)
                    {
                        var index = parseInt(d[0].substring(13));
                        if(reviewers[index].type == 'projectRole') reviewers[index].projectRoles.push(d[1]); // 项目角色
                    }
                    else if(d[0].indexOf('executionRoles') === 0)
                    {
                        var index = parseInt(d[0].substring(15));
                        if(reviewers[index].type == 'executionRole') reviewers[index].executionRoles.push(d[1]); // 执行角色
                    }
                    else if(d[0].indexOf('superiorList') === 0)
                    {
                        var index = parseInt(d[0].substring(13));
                        if(reviewers[index].type == 'superiorList') reviewers[index].superiorList = d[1]; // 连续上级
                    }
                    else if(d[0].indexOf('users') === 0)
                    {
                        var index = parseInt(d[0].substring(6));
                        if(reviewers[index].type == 'appointee') reviewers[index].users.push(d[1]); // 指定人员
                    }
                    else if(d[0].indexOf('rangeuser') === 0)
                    {
                        var index = parseInt(d[0].substring(10));
                        if(reviewers[index].type == 'select') reviewers[index].users.push(d[1]); // 人员范围
                    }
                    else if(d[0].indexOf('rangerole') === 0)
                    {
                        var index = parseInt(d[0].substring(10));
                        if(reviewers[index].type == 'select') reviewers[index].roles.push(d[1]); // 角色范围
                    }
                    else if(d[0].indexOf('rangeposition') === 0)
                    {
                        var index = parseInt(d[0].substring(14));
                        if(reviewers[index].type == 'select') reviewers[index].positions.push(d[1]); // 职位范围
                    }
                    else if(d[0].indexOf('rangedept') === 0)
                    {
                        var index = parseInt(d[0].substring(10));
                        if(reviewers[index].type == 'select') reviewers[index].depts.push(d[1]); // 部门范围
                    }
                    else if(d[0].indexOf('userRange') === 0)
                    {
                        var index = parseInt(d[0].substring(10));
                        if(reviewers[index].type == 'select') reviewers[index].userRange = d[1]; // 指定范围
                    }
                    else if(d[0].indexOf('required') === 0)
                    {
                        var index = parseInt(d[0].substring(9));
                        if(reviewers[index].type == 'select') reviewers[index].required = d[1]; // 审批人必填
                    }
                    if(d[0] == 'needAll')     data.needAll     = 1;
                    if(d[0] == 'percent')     data.percent     = d[1];
                    if(d[0] == 'multiple')    data.multiple    = d[1];
                    if(d[0] == 'commentType') data.commentType = d[1];
                    if(d[0] == 'agentType')   data.agentType   = d[1];
                    if(d[0] == 'deletedType') data.deletedType = d[1];
                    if(d[0] == 'selfType')    data.selfType    = d[1];
                    if(d[0] == 'agentUser' && data.agentType   == 'appointee') data.agentUser = d[1];
                    if(d[0] == 'setUser'   && data.deletedType == 'setUser')   data.setUser   = d[1];
                    if(d[0] == 'priv')
                    {
                        if(!data.priv) data.priv = [];
                        data.priv.push(d[1]);
                    }
                }
            }

            if(data.reviewType != 'reject')
            {
                if(d[0].indexOf('ccType') === 0)  // 设置抄送
                {
                    var index = parseInt(d[0].substring(7));
                    ccs[index] = {type: d[1]};   // 支持指定人员，发起人自选，上级，角色
                    if(d[1] == 'select')
                    {
                        ccs[index].users     = [];
                        ccs[index].roles     = [];
                        ccs[index].depts     = [];
                        ccs[index].positions = [];
                    }
                    if(d[1] == 'appointee') ccs[index].users     = []; // 指定人员
                    if(d[1] == 'role')      ccs[index].roles     = []; // 角色
                    if(d[1] == 'position')  ccs[index].positions = []; // 职位
                }
                else if(d[0].indexOf('ccusers') === 0) //设置抄送人
                {
                    var index = parseInt(d[0].substring(8));
                    if(ccs[index].type == 'appointee') ccs[index].users.push(d[1]);
                }
                else if(d[0].indexOf('ccroles') === 0) //设置抄送角色
                {
                    var index = parseInt(d[0].substring(8));
                    if(ccs[index].type == 'role') ccs[index].roles.push(d[1]);
                }
                else if(d[0].indexOf('ccpositions') === 0) //设置抄送职位
                {
                    var index = parseInt(d[0].substring(12));
                    if(ccs[index].type == 'position') ccs[index].positions.push(d[1]);
                }
                else if(d[0].indexOf('ccrangeuser') === 0) // 人员范围
                {
                    var index = parseInt(d[0].substring(12));
                    if(ccs[index].type == 'select') ccs[index].users.push(d[1]);
                }
                else if(d[0].indexOf('ccrangerole') === 0) // 角色范围
                {
                    var index = parseInt(d[0].substring(12));
                    if(ccs[index].type == 'select') ccs[index].roles.push(d[1]);
                }
                else if(d[0].indexOf('ccrangeposition') === 0) // 职位范围
                {
                    var index = parseInt(d[0].substring(16));
                    if(ccs[index].type == 'select') ccs[index].positions.push(d[1]);
                }
                else if(d[0].indexOf('ccrangedept') === 0) // 部门范围
                {
                    var index = parseInt(d[0].substring(12));
                    if(ccs[index].type == 'select') ccs[index].depts.push(d[1]);
                }
                else if(d[0].indexOf('ccuserRange') === 0) // 指定范围
                {
                    var index = parseInt(d[0].substring(12));
                    if(ccs[index].type == 'select') ccs[index].userRange = d[1];
                }
            }
        }

        if(node.type == 'approval')
        {
            data.reviewers = [];
            for(var i in reviewers) data.reviewers.push(reviewers[i]);
        }

        data.ccs = [];
        for(var i in ccs) data.ccs.push(ccs[i]);

        var message = checkNode(node.type, data)
        if(message)
        {
            zui.Messager.show({content:message, close: false});
            return;
        }
        father[index1] = data;

        $.post(link, {nodes: JSON.stringify(nodes), 'type': 'update'}, function(data)
        {
            $('body').data('zui.Modal').hide();
            $('#editor').html($(data).find('#editor').html()).zuiInit();
        });
    });

    return false;
}

window.checkNode = function(type, data)
{
    var reviewerSelectCount = 0;
    if(typeof data.reviewers === 'undefined' || data.reviewers.length == 0)
    {
        if(type == 'approval' && data.reviewType == 'manual') return warningLang['needReviewer'];
    }
    else
    {
        for(var reviewer of data.reviewers)
        {
            if(reviewer.type == 'appointee' && (reviewer.users.length === 0 || reviewer.users == ''))     return warningLang['selectUser'];
            if(reviewer.type == 'role'      && (reviewer.roles.length === 0 || reviewer.roles == ''))     return warningLang['selectRole'];
            if(reviewer.type == 'position'  && (reviewer.positions.length === 0 || reviewer.roles == '')) return warningLang['selectPosition'];
            if(reviewer.type == 'select' || reviewer.type == 'setByPrev')
            {
                if(reviewerSelectCount !== 0) return warningLang['oneSelect'];
                reviewerSelectCount ++;
            }
        }
    }

    var ccSelectCount = 0;
    if(typeof data.ccs !== 'undefined')
    {
        for(var cc of data.ccs)
        {
            if(cc.type == 'appointee' && (cc.users.length === 0 || cc.users == ''))         return warningLang['selectUser'];
            if(cc.type == 'role'      && (cc.roles.length === 0 || cc.roles == ''))         return warningLang['selectRole'];
            if(cc.type == 'position'  && (cc.positions.length === 0 || cc.positions == '')) return warningLang['selectPosition'];
            if(cc.type == 'select')
            {
                if(ccSelectCount !== 0) return warningLang['oneSelect'];
                ccSelectCount ++;
            }
        }
    }

    return '';
}

window.renderRowData = function($row, index, row)
{
    if(row)
    {
        if(row.type || row.ccType)
        {
            const type = row.type ? row.type : row.ccType;
            $row.find('[data-name=approval] .picker-box').addClass('hidden');
            $row.find("[data-type='" + type + "']").removeClass('hidden');

            if(row.type == 'select') $row.find('[data-type=required]').removeClass('hidden');
            if(type == 'self' || type == 'upLevel' || type == 'setByPrev' || type == 'superior')
            {
                $row.find('[data-name=type]').attr('colspan', '2');
                $row.find('[data-name=ccType]').attr('colspan', '2');
                $row.find('[data-name=approval]').addClass('hidden');
            }
            else
            {
                $row.find('[data-name=type]').attr('colspan', '1');
                $row.find('[data-name=ccType]').attr('colspan', '1');
                $row.find('[data-name=approval]').removeClass('hidden');
            }
        }
        if(row.userRange || row.ccuserRange)
        {
            const option = row.userRange ? ('range' + row.userRange) : ('ccrange' + row.ccuserRange);
            $row.find('[data-type=range]').addClass('hidden');
            $row.find('[data-type=ccrange]').addClass('hidden');
            $row.find('[data-type=' + option + ']').removeClass('hidden');
        }
    }
}

window.renderConditionRowData = function($row, index, row)
{
    // 第一行不显示逻辑运算符控件
    if(index == 0) $row.find('[data-name=conditionLogical] > div').addClass('hidden');

    // ajax获取字段控件
    let field;
    let value;
    if(typeof row !== 'undefined')
    {
        // 条件有数据的情况
        field = row?.conditionField;
        value = row?.conditionValue;
    }
    else
    {
        // 条件无数据的情况
        field = 'submitUsers';
        value = '';
    }

    changeFieldControl($row, index, field, value);
}

window.changeFieldControl = function($row, index, field, value)
{
    const url = $.createLink('approvalflow', 'ajaxGetFieldControl', `field=${field}&module=${workflow}`);
    $.get(url, function(data)
    {
        data = JSON.parse(data);

        $row.find('[data-name=conditionValue]').html(`<div class='controlBox'></div>`);

        const controlBox = $row.find('.controlBox');
        const options    = data.options;
        const control    = data.control;
        const name       = `conditionValue[${index + 1}]`;

        if(control == 'picker')
        {
            new zui.Picker(controlBox, {
                items: options,
                defaultValue: value,
                name: name,
                required: true
            });
        }
        else if(control == 'datePicker')
        {
            const datePicker = new zui.DatePicker(controlBox, {name: name});
            setTimeout(() => datePicker.$.setValue(value), 50);
        }
        else if(control == 'datetimePicker')
        {
            const datetimePicker = new zui.DatetimePicker(controlBox, {name: name});
            setTimeout(() => datetimePicker.$.setValue(value), 50);
        }
        else
        {
            controlBox.html(`<input type='text' class='form-control' name='${name}' value='${value}' required>`);
        }
    });
}

window.changeReviewType = function(event)
{
    $('#reviewTypeBox').toggleClass('hidden', $(event.target).val() == 'reject');
    $('#reviewTypeBox li[data-key=reviewer]').toggleClass('hidden', $(event.target).val() == 'pass');
    $('#reviewTypeBox li[data-key=approvalflow]').toggleClass('hidden', $(event.target).val() == 'pass');
    if($(event.target).val() == 'pass')   $('#reviewTypeBox li[data-key=ccer] > a').trigger('click');
    if($(event.target).val() == 'manual') $('#reviewTypeBox li[data-key=reviewer] > a').trigger('click');
}

window.changeType = function(event)
{
    const $row = $(event.target).closest('tr');
    const type = $(event.target).val();
    $row.find('[data-name=approval] .picker-box').addClass('hidden');

    $row.find("[data-type='" + type + "']").removeClass('hidden');
    $row.find("[data-type='" + type + "'] .pick-value").trigger('change');

    if(type == 'select') $row.find('[data-type=required]').removeClass('hidden');
    if(type == 'self' || type == 'upLevel' || type == 'setByPrev' || type == 'superior')
    {
        $row.find('[data-name=type]').attr('colspan', '2');
        $row.find('[data-name=ccType]').attr('colspan', '2');
        $row.find('[data-name=approval]').addClass('hidden');
    }
    else
    {
        $row.find('[data-name=type]').attr('colspan', '1');
        $row.find('[data-name=ccType]').attr('colspan', '1');
        $row.find('[data-name=approval]').removeClass('hidden');
    }
}

window.changeOption = function(event)
{
    const $row   = $(event.target).closest('tr');
    const option = $(event.target).val();

    if(!option || typeof(option) != 'string') return;

    $row.find('.picker-box[data-name^=range]').addClass('hidden');
    $row.find(".picker-box[data-name^='range" + option + "']").removeClass('hidden');

    $row.find('.picker-box[data-name^=ccrange]').addClass('hidden');
    $row.find(".picker-box[data-name^='ccrange" + option + "']").removeClass('hidden');
}

window.changeMultiple = function(event)
{
    $('#reviewTypeBox [name=needAll]').parent().addClass('hidden');

    $(event.target).parent().find('[name=needAll]').parent().removeClass('hidden');
    $('#reviewTypeBox [name=percent]').parent().toggleClass('hidden', $(event.target).val() != 'percent');
}

window.changeAgentType = function(event)
{
    $('#reviewTypeBox [name=agentUser]').closest('.picker-box').toggleClass('hidden', $(event.target).val() != 'appointee');
}

window.changeDeletedType = function(event)
{
    $('#reviewTypeBox [name=setUser]').closest('.picker-box').toggleClass('hidden', $(event.target).val() != 'setUser');
}

window.changeConditionField = function(event)
{
    const $row = $(event.target).closest('tr');
    const index = $row.data('index');
    const field = $(event.target).val();

    changeFieldControl($row, index, field, '');
}

window.checkPercent = function(e)
{
    const percent = parseInt(e.target.value);
    if(percent < 1 || percent > 100 || isNaN(percent) || percent != e.target.value)
    {
        $('#percent').val('50');
        zui.Modal.alert(warningLang['percent']);
    }
}

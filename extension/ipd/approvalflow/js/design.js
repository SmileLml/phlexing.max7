$(document).ready(function() {
    nodes = JSON.parse(nodes);

    $(window).resize(function() {
        render(nodes, false);
    });
    render(nodes, false);

    $('#submit').click(function() {
        if(nodes.length <= 2) {
            new $.zui.Messager(warningLang['needReview'], { close: false }).show();
            return false;
        }

        var nodesText = JSON.stringify(nodes);
        $('#nodes').val(nodesText);
        isChanged = false;
    })

    /* warning for save. 提示保存修改. */
    window.onbeforeunload = function() {
        if(isChanged) {
            return warningLang['save'];
        }
    }
});

var isChanged = false;
var modal;

function genID() {
    return Math.random().toString(36).substr(2)
}

function genUserOptions(selectedUsers) {
    var options = '';
    for(let account in users) {
        options += '<option ' + (selectedUsers.indexOf(account) != -1 ? 'selected' : '') + ' value="' + account + '">' + users[account] + '</option>';
    }
    return options;
}

function genConditionSelect(index, cond) {
    if(typeof cond.type == 'undefined') {
      cond.type       = 'user';
      cond.selectType = 'account';
      cond.users      = [];
    }

    html  = '<div class="form-title">' + nodeTypeLang['condition'] + '</div>';
    html += '<div class="detail"><div class="detail-title">' + noticeTypeLang['when'] + '</div><div class="detail-content">';
    html += '<select id="type' + index + '" name="type' + index + '" class="form-control chosen">';
    for(var key in conditionTypeLang) {
        html += '<option ' + (cond.type == key ? 'selected' : '') + ' value="' + key + '">' + conditionTypeLang[key].name + '</option>';
    }
    html += '</select></div></div>';

    html += '<div class="detail"><div class="detail-title">' + noticeTypeLang['type'] + '</div><div class="detail-content">';
    html += '<select id="selectType' + index + '" name="selectType' + index + '" class="form-control chosen">';
    for(var key in conditionTypeLang[cond.type].selectType) {
        var select = conditionTypeLang[cond.type].selectType[key];
        html += '<option ' + (cond.selectType == key ? 'selected' : '') + ' value="' + key + '">' + select.name + '</option>';
    }
    html += '</select></div></div>';

    var select = conditionTypeLang[cond.type].selectType[cond.selectType];
    html += '<div class="detail condition-select"><div class="detail-title">' + select.tips + '</div><div class="detail-content"><select id="condition-role" name="' + select.options + index + '" multiple class="form-control chosen">';
    html += genOptions(select.options, cond);
    html += '</select></div></div>';

    return html;
}

function genOptions(opType, node) {
    if(!opType) return [];

    var data = {roles, users, depts, positions, productRoles, projectRoles, executionRoles, userRange, superiorList};
    var options = '';
    for(let key in data[opType]) {
        let selected = typeof node[opType] == 'undefined' || node[opType].indexOf(key) == -1 ? '' : 'selected';
        if(key == '2' && opType == 'superiorList' && typeof node[opType] == 'undefined') selected = 'selected';

        options += '<option ' + selected + ' value="' + key + '">' + data[opType][key] + '</option>';
    }
    return options;
}

function genDeptOptions(selectedDepts) {
    var options = '';
    for(let id in depts) {
        options += '<option ' + (selectedDepts.indexOf(id) != -1 ? 'selected' : '') + ' value="' + id + '">' + depts[id] + '</option>';
    }
    return options;
}

function bindConditionEvent(node) {
    $('.form-condition .close').click(function() {
        $(this).parent().remove();
        return false;
    })

    $('select[name^="selectType"]').change(function(e) {
        var index = $(this).attr("name").substring(10);
        var cond  = node.conditions[index];
        if(typeof cond == 'undefined') {
          cond = {type: 'user'};
        }

        cond.selectType = $(this).val();
        $(this).closest('.condition-block').html(genConditionSelect(index, cond));
        $('.chosen').chosen();
        bindConditionEvent(node);
    })
}

function bindNodeEvent() {
    $('.chosen').chosen();
    $('.form-reviewer .close').click(function() {
        if($('.form-reviewer').length <= 1) { return false; }
        $(this).parent().remove();
    })
    $('.form-cc .close').click(function() {
        if($(this).closest('form').hasClass('cc') && $('.form-cc').length <= 1) { return false; }
        $(this).parent().remove();
    })

    $('input[name="reviewType"]').change(function(e) {
        if($(this).val() != 'manual') {
            $('.reviewer-block').addClass('hidden');
            $('.multiple-block').addClass('hidden');
            $('.agent-block').addClass('hidden');
            $('.addReviewer').addClass('hidden');
            if($(this).val() == 'pass') {
                $('.addCC').removeClass('hidden');
            } else {
                $('.addCC').addClass('hidden');
            }
        } else {
            $('.addReviewer').removeClass('hidden');
            $('.addCC').removeClass('hidden');
            $('.reviewer-block').removeClass('hidden');
            $('.multiple-block').removeClass('hidden');
            $('.agent-block').removeClass('hidden');
            $('.cc-block').removeClass('hidden');
        }
    })

    $("input[name='multiple']").change(function() {
        $('input[name="needAll"]').closest('.checkbox-primary').addClass('hidden');
        $('input[name="needAll"]').prop('checked', false);
        $('.percent-box').addClass('hidden');

        $(this).closest('div').find('input[name="needAll"]').closest('.checkbox-primary').removeClass('hidden');

        if($(this).val() == 'and' || $(this).val() == 'percent') {
            $(this).closest('div').find('.percent-box').removeClass('hidden');
        }
    })

    $('input[name="percent"]').blur(function() {
        var percent = parseInt($(this).val());
        if(percent < 1 || percent > 100 || isNaN(percent) || percent != $(this).val()) {
            $(this).val('50');
            alert(warningLang['percent']);
        }
    })

    $('select[name^="type"]').change(function(e) {
        var type = $(this).val();
        var options = reviewerTypeLang[type].options;
        var $pa = $(this).parents('.reviewer-block');
        $pa.find('.select-box').each(function() {
            if($(this).hasClass(options + '-select')) {
                $(this).removeClass('hidden')
            } else {
                $(this).addClass('hidden')
            }
        });

        if(type == 'superiorList')
        {
            $(this).closest('form').find('input[name^="multiple"][value="and"]').prop('checked', true);
            $(this).closest('form').find('input[name^="multiple"]').attr('disabled', true);
            $(this).closest('form').find('input[name^="multiple"]').change();
        }
        else
        {
            $(this).closest('form').find('input[name^="multiple"]').attr('disabled', false);
        }
    })

    $('select[name^="type"]').change();

    $('select[name^="userRange"],select[name^=ccuserRange]').change(function(e) {
        var range = $(this).val();
        $(this).closest('.userRange-select').find('.select-box').each(function() {
            if($(this).hasClass('range-' + range + '-select')) {
                $(this).removeClass('hidden')
            } else {
                $(this).addClass('hidden')
            }
        });
    })

    $('select[name^="ccType"]').change(function(e) {
        var type = $(this).val();
        var options = reviewerTypeLang[type].options;
        var $pa = $(this).parents('.cc-block');
        $pa.find('.select-box').each(function() {
            if($(this).hasClass(options + '-select')) {
                $(this).removeClass('hidden')
            } else {
                $(this).addClass('hidden')
            }
        });
    })

    $('input[name="agentType"]').change(function() {
        if($(this).val() == 'appointee') {
            $('.agentUser').removeClass('hidden');
        } else {
            $('.agentUser').addClass('hidden');
        }
    })

    $('input[name="deletedType"]').change(function() {
        if($(this).val() == 'setUser') {
            $('.setUser').removeClass('hidden');
        } else {
            $('.setUser').addClass('hidden');
        }
    })
}

function showAddType(el) {
    hideAddType();

    var addText = '<div class="add-node-types" style="pointer-events: auto;"><div class="node-type add-reviewer"><i class="node-type-icon iconfont-approval-admin icon-review"></i><div class="node-type-name">审批人</div></div><div class="node-type add-cc"><i class="node-type-icon iconfont-approval-admin icon-send"></i><div class="node-type-name">抄送人</div></div><div class="node-type add-branch type-condition"><i class="node-type-icon iconfont-approval-admin icon-treemap"></i><div class="node-type-name">条件分支</div></div><div class="node-type add-branch type-parallel"><i class="node-type-icon iconfont-approval-admin icon-tree"></i><div class="node-type-name">并行分支</div></div></div>';
    $(el).addClass('add-node-btn-active');
    $(el).append(addText);

    /* 点击添加评审人 */
    $('.add-reviewer').click(function() {
        var $pa = $(this).closest('.editor-node');
        var isCondition = $pa.hasClass('condition');
        if(isCondition) {
            $pa = $pa.parent().closest('.editor-node');
        }
        var id = $pa.data('id');

        getNode(id, function(index, father, index2, grandpa) {
            index = isCondition ? parseInt(index) : parseInt(index) + 1;
            if(isCondition) {
                father = (index == 'default') ? father.default.nodes : father.branches[index].nodes;
                index = 0;
            }
            var reviewNode = {id: genID(), title: nodeTypeLang['approval'], reviewType: "manual", type: "approval", reviewers: [{type: "select"}], agentType: "pass", ccs: []};
            father.splice(index, 0, reviewNode);
            render(nodes);
        })
    })

    $('.add-cc').click(function() {
        var $pa = $(this).closest('.editor-node');
        var isCondition = $pa.hasClass('condition');
        if(isCondition) {
            $pa = $pa.next('.editor-node');
        }
        var id = $pa.data('id');

        getNode(id, function(index, father, index2, grandpa) {
            index = isCondition ? parseInt(index) : parseInt(index) + 1;

            var reviewNode = {id: genID(), title: nodeTypeLang['cc'], type: "cc", ccs: [{type: "select"}]};
            father.splice(index, 0, reviewNode);
            render(nodes);
        })
    })

    $('.add-branch').click(function() {
        var $pa = $(this).closest('.editor-node');
        var isCondition = $pa.hasClass('condition');
        if(isCondition) {
            $pa = $pa.next('.editor-node');
        }
        var id = $pa.data('id');
        var branchType = $(this).hasClass('type-condition') ? 'condition' : 'parallel';

        getNode(id, function(index, father, index2, grandpa) {
            index = isCondition ? parseInt(index) : parseInt(index) + 1;

            // var reviewNode = {id: genID(), type: "approval", reviewers: [{type: "select"}]};
            var branchNode = {
                id: genID(),
                type: "branch",
                branchType: branchType,
                branches: [{
                    id: genID(),
                    conditions: [],
                    nodes:[{id: genID(), type: "approval", reviewers: [{type: "select"}]}]
                }],
                default: {
                    id: genID(),
                    nodes: [{id: genID(), type: "approval", reviewers: [{type: "select"}]}]
                }
            };
            father.splice(index, 0, branchNode);
            render(nodes);
        })
    })
}

function hideAddType(el) {
    $('.add-node-types').remove();
    $('.add-node-btn-active').removeClass('add-node-btn-active');
    if(typeof el != 'undefined') {
        el.stopPropagation();
    }
}

$('#editor').click(function(el) {
    hideAddType(el);

    $('.panel').removeClass('visible');
    el.stopPropagation();
})

// Render
function getNode(nodeID, callback) {
    if(nodeID == 'start') {
        callback(0, nodes)
        return [0];
    } else if(nodeID == 'end') {
        callback(nodes.length-1, nodes)
        return [nodes.length-1];
    } else {
        return findFromNodes(nodeID, nodes, callback);
    }
}

function findFromNodes(nodeID, nodes, callback, parentIndex) {
    for(let index in nodes) {
        var node = nodes[index];
        if(node.id == nodeID) {
            callback(index, nodes, parentIndex ? (parseInt(parentIndex) + parseInt(index)) : index);
            return [index];
        }

        if(node.type == 'branch') {
            for(var index2 in node.branches) {
                var path   = [index, 'branches', index2];
                var branch = node.branches[index2];

                if(branch.id == nodeID) {
                    callback(index2, node, index, nodes);
                    return path;
                }

                var result = findFromNodes(nodeID, branch.nodes, callback, index);
                if(result.length > 0) {
                    path.push('nodes');
                    return path.concat(result);
                }
            }

            // default
            var path = [index, 'default'];
            var branch = node.default;
            if(branch.id == nodeID) {
                callback('default', node, index, nodes);
                return path;
            }

            var result = findFromNodes(nodeID, branch.nodes, callback, index);
            if(result.length > 0) {
              return path.concat(result);
            }
        }
   }

   return [];
}

function render(nodes, modify) {
    if(typeof modify === 'undefined' || modify === true) {
        isChanged = true;
    }

    var offsetTop  = $('#graph').scrollTop();
    var offsetLeft = $('#graph').scrollLeft();

    $('#root').empty();

    var graphHeight = $(window).height() - $('#header').outerHeight() - 100;
    $('#graph').css('height', graphHeight);

    var $root = $('#root');
    $root.append(renderNodes(nodes));

    $('i.delete-btn').click(function() {
        var id = $(this).closest('.editor-node').data('id');
        getNode(id, function(index, father) {
          father.splice(index,1)
        })

        render(nodes);
    });

    $('button.delete-btn').click(function() {
        var id = $(this).closest('.branch').data('id');
        getNode(id, function(index, father, index2, grandpa) {
            father.branches.splice(index, 1);
            if(father.branches.length == 0) {
                grandpa.splice(index2, 1)
            }
        })

        render(nodes);
    });

    $('#graph').scrollTop(offsetTop).scrollLeft(offsetLeft);

    // Actions.
    $('.add-node-btn').click(function(el) {
        showAddType(this);
        el.stopPropagation();
    })

    $('.add-condition').click(function() {
        var id = $(this).closest('.editor-node').data('id');
        getNode(id, function(index, father) {
            father[index].branches.push({
                id: genID(),
                conditions: [],
                nodes:[{id: genID(), reviewType: "manual", type: "approval", reviewers: [{type: "select"}]}]
            });
            render(nodes);
        });
    })

    function renderCondition(index, cond)
    {
        var type = cond.type ? cond.type : 'user';
        html  = '<div class="form-condition condition-block"><button class="close">×</button>';
        html += genConditionSelect(index, cond);
        html += '</div>';

        return html;
    }

    $('.condition .node-content').click(function() {
      var $pa = $(this).closest('.editor-node').parent().closest('.editor-node');
      var id = $pa.data('id');
      getNode(id, function(index, father, index2, grandpa) {
          if(index == 'default') return;

          var selectedFather = father.branches;
          var selectedIndex  = index;

          modal = new $.zui.ModalTrigger({title: noticeTypeLang['setCondition'], custom: function(el) {
              var node = selectedFather[selectedIndex];

              var html = '<form id="edit" onsubmit="javascript:saveCondition(\''+ id + '\');return false;">';
              if(node.conditions.length == 0) {
                  node.conditions.push({});
              }
              for(var index in node.conditions) {
                  var cond = node.conditions[index];
                  html += renderCondition(index, cond);
              }
              html += '<div class="addCondition detail"><button class="btn btn-link">+ ' + noticeTypeLang['addCond'] + '</button><span>' + noticeTypeLang['conditionOr'] + '</span></div>';

              html += '<div class="detail text-center"><button class="btn btn-wide btn-primary form-saveCondition" type="submit">' + noticeTypeLang['confirm'] + '</button></div>';
              html += '</form>';

              return html;
          }});

          modal.show({onShow: function() {
            setTimeout(function() {
              $('.chosen').chosen();

              $('.addCondition .btn').click(function() {
                var cond = {type: 'user', selectType: 'account', users: []};
                $(this).parent().before(renderCondition(genID(), cond));
                bindConditionEvent(selectedFather[selectedIndex]);
                $('.chosen').chosen();
                return false;
              })

              bindConditionEvent(selectedFather[selectedIndex]);
            }, 20)
            return false;
          }})
          modal.show();
      })
    })

    function renderReviewer(reviewer, index, nodeIndex) {
        var html = '';
        var type = reviewer.type ? reviewer.type : 'select';
        html += '<div class="form-reviewer reviewer-block"><button class="close">×</button><div class="form-title">' + userTypeLang['reviewer'] + '</div>';
        html += '<div class="detail"><div class="detail-title">' + noticeTypeLang['reviewType'] + '</div><div class="detail-content"><select id="type' + index + '" name="type' + index + '" class="form-control chosen">';
        for(let rp in reviewerTypeLang) {
            if(nodeIndex <= 1 && rp == 'setByPrev') continue;
            html += '<option ' + (type == rp ? 'selected' : '') + ' value="' + rp + '">' + reviewerTypeLang[rp].name + '</option>';
        }
        html += '</select></div></div>';


        var opType = reviewerTypeLang[type].options;
        var types = {'': true};
        for(let reviewType in reviewerTypeLang) {
            var info = reviewerTypeLang[reviewType];
            if(typeof types[info.options] !== 'undefined') {
              continue;
            }
            types[info.options] = true;

            html += '<div class="select-box detail ' + info.options + '-select ' + (info.options == opType ? '' : 'hidden') + '">';
            if(info.options) {
                var multiple = (info.options != 'userRange' && info.options != 'superiorList') ? 'multiple' : '';
                html += '<div class="detail-title">' + info.tips + '</div><div class="detail-content"><select id="' + info.options + index + '" name="' + info.options + index + '" class="form-control chosen" ' + multiple + '>' + genOptions(info.options, reviewer) + '</select></div>';

                if(info.options == 'userRange') {
                    for(let range in userRange) {
                        if(range == 'all') continue;
                        html += '<div class="select-box detail range-' + range + '-select ' + (range == reviewer.userRange ? '' : 'hidden') + '"><div class="detail-title">' + select + userRange[range] + '</div><div class="detail-content"><select id="range' + range + index + '" name="range' + range + index + '" class="form-control chosen" multiple>' + genOptions(range + 's', reviewer) + '</select></div></div>';
                    }
                }
            }
            html += '</div>';
        }

        html += '</div>';

        return html;
    }

    function renderCC(cc, index) {
        var html = '';
        var type = cc.type ? cc.type : 'select';
        html += '<div class="form-cc cc-block"><button class="close">×</button><div class="form-title">' + userTypeLang['cc'] + '</div>';
        html += '<div class="detail"><div class="detail-title">' + noticeTypeLang['ccType'] + '</div><div class="detail-content"><select id="ccType' + index + '" name="ccType' + index + '" class="form-control chosen">';
        for(let rp in reviewerTypeLang) {
            if(rp == 'self' || rp == 'setByPrev' || rp == 'productRole' || rp == 'projectRole' || rp == 'executionRole') continue;
            html += '<option ' + (type == rp ? 'selected' : '') + ' value="' + rp + '">' + reviewerTypeLang[rp].name + '</option>';
        }
        html += '</select></div></div>';

        var opType = reviewerTypeLang[type].options;
        var types = {'': true};
        for(let reviewType in reviewerTypeLang) {
            var info = reviewerTypeLang[reviewType];
            if(typeof types[info.options] !== 'undefined') {
              continue;
            }
            types[info.options] = true;

            html += '<div class="select-box detail ' + info.options + '-select ' + (info.options == opType ? '' : 'hidden') + '">';
            if(info.options) {
                var multiple = info.options != 'userRange' ? 'multiple' : '';
                html += '<div class="detail-title">' + info.tips + '</div><div class="detail-content"><select id="cc' + info.options + index + '" name="cc' + info.options + index + '" class="form-control chosen" ' + multiple + '>' + genOptions(info.options, cc) + '</select></div>';
                if(info.options == 'userRange') {
                    for(let range in userRange) {
                        if(range == 'all') continue;
                        html += '<div class="select-box detail range-' + range + '-select ' + (range == cc.userRange ? '' : 'hidden') + '"><div class="detail-title">' + select + userRange[range] + '</div><div class="detail-content"><select id="ccrange' + range + index + '" name="ccrange' + range + index + '" class="form-control chosen" multiple>' + genOptions(range + 's', cc) + '</select></div></div>';
                    }
                }
            }
            html += '</div>';
        }

        html += '</div>';

        return html;
    }

    /* 设置开始 结束 抄送节点 */
    $('.start .node-content, .end .node-content, .cc .node-content').click(function() {
        var $pa = $(this).closest('.editor-node');
        var id = $pa.data('id');
        getNode(id, function(index, father, index2, grandpa) {
            var selectedFather = father;
            var selectedIndex  = index;
            var node  = selectedFather[selectedIndex];
            modal = new $.zui.ModalTrigger({title: noticeTypeLang['set'] + nodeTypeLang[node.type] + noticeTypeLang['node'], custom: function(el) {
                var title = node.title ? node.title : nodeTypeLang[node.type];
                var html = '<form class="' + node.type + '" id="edit" onsubmit="javascript:saveNode(\''+ id + '\');return false;">';
                html += '<div class="detail ' + (node.type == 'cc' ? '' : 'hidden') + '"><div class="detail-title">' + noticeTypeLang['title'] + '</div><div class="detail-content"><input type="text" id="title" name="title" class="form-control" value="' + title + '"></div></div>';
                var ccs = node.ccs ? node.ccs : [];
                for(var index in ccs) {
                  var cc = ccs[index];
                  html += renderCC(cc, index);
                }

                html += '<div class="detail"><button class="addCC btn btn-link">+ ' + noticeTypeLang['addCC'] + '</button></div>';
                html += '<div class="detail text-center"><button class="btn btn-wide btn-primary form-saveNode" type="submit">' + noticeTypeLang['confirm'] + '</button></div>';
                html += '</form>';

                return html;
            }});
            modal.show({onShow: function() {
                setTimeout(function() {
                  bindNodeEvent();
                  $('.addCC').click(function() {
                      $(this).parent().before(renderCC({type: 'select'}, genID()));
                      bindNodeEvent();
                      return false;
                  })
                }, 20)
            }})
        })
    })

    $('.approval .node-content').click(function() {
        var $pa = $(this).closest('.editor-node');
        var id = $pa.data('id');
        getNode(id, function(index, father, index2, grandpa) {
            var selectedFather = father;
            var selectedIndex  = index;

            modal = new $.zui.ModalTrigger({title: noticeTypeLang['setNode'], custom: function(el) {
                var reviewIndex = 0;
                var ccIndex     = 0;

                var node  = selectedFather[selectedIndex];
                var title = node.title ? node.title : nodeTypeLang[node.type];
                var reviewType = node.reviewType ? node.reviewType : 'manual';
                var html = '<form class="approval" id="edit" onsubmit="javascript:saveNode(\''+ id + '\');return false;">';
                if(node.type == 'approval' || node.type == 'cc') html += '<div class="detail"><div class="detail-title">' + noticeTypeLang['title'] + '</div><div class="detail-content"><input type="text" id="title" name="title" class="form-control" value="' + title + '"></div></div>';
                html += '<div class="detail"><div class="detail-title">' + noticeTypeLang['reviewType'] + '</div><div class="detail-content"><label class="radio-inline"><input type="radio" name="reviewType" ' + (reviewType == 'manual' ? 'checked' : '') + ' value="manual">' + reviewTypeLang['manual'] + '</label><label class="radio-inline"><input type="radio" name="reviewType" ' + (reviewType == 'pass' ? 'checked' : '') + ' value="pass">' + reviewTypeLang['pass'] + '</label><label class="radio-inline"><input type="radio" name="reviewType" ' + (reviewType == 'reject' ? 'checked' : '') + ' value="reject">' + reviewTypeLang['reject'] + '</label></div></div>';
                for(var index in node.reviewers) {
                    var reviewer = node.reviewers[index];
                    html += renderReviewer(reviewer, index, index2);
                    reviewIndex = index;
                }
                html += '<div class="addReviewer detail ' + (reviewType == 'manual' ? '' : 'hidden') + '"><button class="btn btn-link">+ ' + noticeTypeLang['addReviewer'] + '</button></div>';

                var multiple = node.multiple ? node.multiple : 'and';
                var needAll  = node.needAll  ? node.needAll  : '';
                var percent  = node.percent  ? node.percent  : 50;
                html += `
                    <div class="detail multiple-block ${reviewType == 'manual' ? '' : 'hidden'}">
                      <div class="detail-title">${noticeTypeLang['multipleType']}</div>
                      <div class="detail-content">
                        <div style='margin-bottom: 5px'>
                          <label class="radio-inline" style='margin-bottom: 5px'>
                            <input type="radio" name="multiple" ${multiple == 'and' ? 'checked' : ''} value="and">
                            ${noticeTypeLang['multipleAnd']}
                          </label>
                          <div class='checkbox-primary ${multiple == 'and' ? '' : 'hidden'}' style='margin-left: 20px;'>
                            <input type='checkbox' name='needAll' ${needAll && multiple == 'and' ? 'checked' : ''}>
                            <label>${needAllLang}</label>
                          </div>
                        </div>
                        <div style='margin-bottom: 5px'>
                          <label class="radio-inline" style='margin-bottom: 5px'>
                            <input type="radio" name="multiple" ${multiple == 'percent' ? 'checked' : ''} value="percent">
                            ${noticeTypeLang['multiplePercent']}
                          </label>
                          <div class='checkbox-primary ${multiple == 'percent' ? '' : 'hidden'}' style='margin-left: 20px; margin-bottom: 5px'>
                            <input type='checkbox' name='needAll' ${needAll && multiple == 'percent' ? 'checked' : ''}>
                            <label>${needAllLang}</label>
                          </div>
                          <div class='input-group ${multiple == 'percent' ? '' : 'hidden'} percent-box' style='margin-left: 20px; width: 100px'>
                            <input name='percent' class='form-control' style='height: 25px' value=${percent}>
                            <span class='input-group-addon'>%</span>
                          </div>
                        </div>
                        <div style='margin-bottom: 5px'>
                          <label class="radio-inline">
                            <input type="radio" name="multiple" ${multiple == 'or' ? 'checked' : ''} value="or">
                            ${noticeTypeLang['multipleOr']}
                          </label>
                          <div class='checkbox-primary ${multiple == 'or' ? '' : 'hidden'}' style='margin-left: 20px;'>
                            <input type='checkbox' name='needAll' ${needAll && multiple == 'or' ? 'checked' : ''}>
                            <label>${needAllLang}</label>
                          </div>
                        </div>
                        <div>
                          <label class="radio-inline">
                            <input type="radio" name="multiple" ${multiple == 'solicit' ? 'checked' : ''} value="solicit">
                            ${noticeTypeLang['multipleSolicit']}
                          </label>
                        </div>
                      </div>
                    </div>`;

                var commentType = node.commentType ? node.commentType : 'noRequired';
                html += '<div class="detail comment-block ' + (reviewType == 'manual' ? '' : 'hidden') + '">';
                html += '<div class="detail-title">' + noticeTypeLang['commentType'] + '</div><div class="detail-content"><label class="radio-inline"><input type="radio" name="commentType" ' + (commentType == 'noRequired' ? 'checked' : '') + ' value="noRequired">' + noticeTypeLang['noRequired'] + '</label><label class="radio-inline"><input type="radio" name="commentType" ' + (commentType == 'required' ? 'checked' : '') + ' value="required">' + noticeTypeLang['required'] + '</label></div>';
                html += '</div>';


                var agent = node.agentType ? node.agentType : 'pass';
                html += '<div class="detail agent-block ' + (reviewType == 'manual' ? '' : 'hidden') + '">';
                html += `
                         <div class="detail-title">${noticeTypeLang['agentType']}</div>
                         <div class="detail-content">
                           <label class="radio-inline">
                             <input type="radio" name="agentType" ${agent === 'pass' ? 'checked' : ''} value="pass">
                             ${noticeTypeLang['agentPass']}
                           </label>
                           <label class="radio-inline">
                             <input type="radio" name="agentType" ${agent === 'reject' ? 'checked' : ''} value="reject">
                             ${noticeTypeLang['agentReject']}
                           </label>
                           <label class="radio-inline">
                             <input type="radio" name="agentType" ${agent === 'appointee' ? 'checked' : ''} value="appointee">
                             ${noticeTypeLang['agentUser']}
                           </label>
                           <label class="radio-inline">
                             <input type="radio" name="agentType" ${agent === 'admin' ? 'checked' : ''} value="admin">
                             ${noticeTypeLang['agentAdmin']}
                           </label>
                         </div>`;

                html += '<div class="agentUser ' + (node.agentType == 'appointee' ? '' : 'hidden') + ' detail-content"><select id="agentUser" name="agentUser" class="form-control chosen">' + genUserOptions([node.agentUser]) + '</select></div>';
                html += '</div>';

                var selfType = node.selfType ? node.selfType : 'selfReview';
                html += '<div class="detail self-block ' + (reviewType == 'manual' ? '' : 'hidden') + '">';
                html += '<div class="detail-title">' + noticeTypeLang['selfType'] + '</div><div class="detail-content"><label class="radio-inline"><input type="radio" name="selfType" ' + (selfType == 'selfReview' ? 'checked' : '') + ' value="selfReview">' + noticeTypeLang['selfReview'] + '</label><label class="radio-inline"><input type="radio" name="selfType" ' + (selfType == 'selfPass' ? 'checked' : '') + ' value="selfPass">' + noticeTypeLang['selfPass'] + '</label><label class="radio-inline"><input type="radio" name="selfType" ' + (selfType == 'selfNext' ? 'checked' : '') + ' value="selfNext">' + noticeTypeLang['selfNext'] + '</label><label class="radio-inline"><input type="radio" name="selfType" ' + (selfType == 'selfManager' ? 'checked' : '') + ' value="selfManager">' + noticeTypeLang['selfManager'] + '</label></div>';
                html += '</div>';

                var deletedType = node.deletedType ? node.deletedType : 'setAdmin';
                html += '<div class="detail deleted-block ' + (reviewType == 'manual' ? '' : 'hidden') + '">';
                html += '<div class="detail-title">' + noticeTypeLang['deletedType'] + '</div><div class="detail-content"><label class="radio-inline"><input type="radio" name="deletedType" ' + (deletedType == 'autoPass' ? 'checked' : '') + ' value="autoPass">' + noticeTypeLang['autoPass'] + '</label><label class="radio-inline"><input type="radio" name="deletedType" ' + (deletedType == 'autoReject' ? 'checked' : '') + ' value="autoReject">' + noticeTypeLang['autoReject'] + '</label><label class="radio-inline"><input type="radio" name="deletedType" ' + (deletedType == 'setUser' ? 'checked' : '') + ' value="setUser">' + noticeTypeLang['setUser'] + '</label><label class="radio-inline"><input type="radio" name="deletedType" ' + (deletedType == 'setSuperior' ? 'checked' : '') + ' value="setSuperior">' + noticeTypeLang['setSuperior'] + '</label><label class="radio-inline"><input type="radio" name="deletedType" ' + (deletedType == 'setManager' ? 'checked' : '') + ' value="setManager">' + noticeTypeLang['setManager'] + '</label><label class="radio-inline"><input type="radio" name="deletedType" ' + (deletedType == 'setAdmin' ? 'checked' : '') + ' value="setAdmin">' + noticeTypeLang['setAdmin'] + '</label></div>';

                html += '<div class="setUser ' + (node.deletedType == 'setUser' ? '' : 'hidden') + ' detail-content"><select id="setUser" name="setUser" class="form-control chosen">' + genUserOptions([node.setUser]) + '</select></div></div>';

                /* cc. */
                var ccs = node.ccs ? node.ccs : [];
                for(var index in ccs) {
                    var cc = ccs[index];
                    html += renderCC(cc, index);
                    ccIndex = index;
                }

                html += '<div class="addCC detail ' + (reviewType != 'reject' ? '' : 'hidden') + '"><button class="btn btn-link">+ ' + noticeTypeLang['addCC'] + '</button></div>';
                html += '<div class="detail text-center"><button class="btn btn-wide btn-primary form-save" type="submit">' + noticeTypeLang['confirm'] + '</button></div>';
                html += '</form>';

                return html;
            }});

            modal.show({onShow: function() {
                setTimeout(function() {
                  bindNodeEvent();
                  $('.addReviewer .btn').click(function() {
                    $(this).parent().before(renderReviewer({type: 'select'}, genID(), index2));
                    bindNodeEvent();
                    return false;
                  })
                  $('.addCC .btn').click(function() {
                    $(this).parent().before(renderCC({type: 'select'}, genID()));
                    bindNodeEvent();
                    return false;
                  })
                }, 20)
            }})
        })
    })
}

function saveCondition(id) {
    var dataArray = $('#edit').serializeArray();
    getNode(id, function(index1, father, index2, grandpa) {
        var conditions = {};
        for(var d of dataArray) {
            if(d.name.indexOf('type') === 0) {
                var index = d.name.substring(4);
                conditions[index] = {type: d.value};
            } else if(d.name.indexOf('selectType') === 0) {
                var index = d.name.substring(10);
                conditions[index].selectType = d.value;
            } else if(d.name.indexOf('roles') === 0) { // 角色
                var index = d.name.substring(5);
                if(typeof conditions[index].roles == 'undefined') conditions[index].roles = [];
                conditions[index].roles.push(d.value);
            } else if(d.name.indexOf('users') === 0) { // 人员
                var index = d.name.substring(5);
                if(typeof conditions[index].users == 'undefined') conditions[index].users = [];
                conditions[index].users.push(d.value);
            } else if(d.name.indexOf('depts') === 0) { // 部门
                var index = d.name.substring(5);
                if(typeof conditions[index].depts == 'undefined') conditions[index].depts = [];
                conditions[index].depts.push(d.value);
            } else if(d.name.indexOf('positions') === 0) { // 职位
                var index = d.name.substring(9);
                if(typeof conditions[index].positions == 'undefined') conditions[index].positions = [];
                conditions[index].positions.push(d.value);
            }
        }

        var message = checkConditions(conditions);
        if(message) {
            new $.zui.Messager(message, { close: false }).show();
            return;
        }
        father.branches[index1].conditions = conditions;
        modal.close();

        render(nodes);
    })
}

function checkConditions(conditions) {
    for(var i in conditions) {
        var condition = conditions[i];
        if(condition.type == 'user') {
            if(condition.selectType == 'account') {
                if(typeof condition.users == 'undefined' || condition.users.length == 0) {
                    return warningLang['selectUser'];
                }
            } else if(condition.selectType == 'dept') {
                if(typeof condition.depts == 'undefined' || condition.depts.length == 0) {
                    return warningLang['selectDept'];
                }
            } else if(condition.selectType == 'role') {
                if(typeof condition.roles == 'undefined' || condition.roles.length == 0) {
                    return warningLang['selectRole'];
                }
            } else if(condition.selectType == 'position') {
                if(typeof condition.positions == 'undefined' || condition.positions.length == 0) {
                    return warningLang['selectPosition'];
                }
            }
        }
    }

    return '';
}

function saveNode(id) {
    var dataArray = $('#edit').serializeArray();
    var data = {};
    getNode(id, function(index1, father, index2, grandpa) {
        var reviewers = {};
        var ccs = {};
        var node = father[index1];
        if(node.type != 'start' && node.type != 'end') data.id = node.id;
        data.type = node.type;

        for(var d of dataArray) {
            if(d.name == 'title') {
                if(node.type == 'approval' || node.type == 'cc') { // 审批和抄送可以设置标题
                  data.title = d.value;
                }
            } else if (node.type == 'approval') { // 只有审批节点可以设置审批人
                if(d.name == 'reviewType') {
                    data.reviewType = d.value;
                    if(d.value != 'manual') {
                      continue;
                    }
                }
                if(data.reviewType == 'manual') {
                    if(d.name.indexOf('type') === 0) {
                        var index = d.name.substring(4);
                        reviewers[index] = {type: d.value};
                        if(d.value == 'select') {
                          reviewers[index].users     = [];
                          reviewers[index].roles     = [];
                          reviewers[index].depts     = [];
                          reviewers[index].positions = [];
                        } else if(d.value == 'appointee') {
                          reviewers[index].users = [];
                        } else if(d.value == 'role') {
                          reviewers[index].roles = [];
                        } else if(d.value == 'positions') {
                          reviewers[index].positions = [];
                        } else if(d.value == 'productRole') {
                          reviewers[index].productRoles = [];
                        } else if(d.value == 'projectRole') {
                          reviewers[index].projectRoles = [];
                        } else if(d.value == 'superiorList') {
                          reviewers[index].superiorList = '';
                        } else if(d.value == 'executionRole') {
                          reviewers[index].executionRoles = [];
                        }
                    } else if(d.name.indexOf('roles') === 0) {
                        var index = d.name.substring(5);
                        if(reviewers[index].type == 'role') { // 角色
                          reviewers[index].roles.push(d.value);
                        }
                    } else if(d.name.indexOf('positions') === 0) {
                        var index = d.name.substring(9);
                        if(reviewers[index].type == 'position') { // 职位
                          reviewers[index].positions.push(d.value);
                        }
                    } else if(d.name.indexOf('productRoles') === 0) {
                        var index = d.name.substring(12);
                        if(reviewers[index].type == 'productRole') { // 产品角色
                          reviewers[index].productRoles.push(d.value);
                        }
                    } else if(d.name.indexOf('projectRoles') === 0) {
                        var index = d.name.substring(12);
                        if(reviewers[index].type == 'projectRole') { // 项目角色
                          reviewers[index].projectRoles.push(d.value);
                        }
                    } else if(d.name.indexOf('executionRoles') === 0) {
                        var index = d.name.substring(14);
                        if(reviewers[index].type == 'executionRole') { // 执行角色
                          reviewers[index].executionRoles.push(d.value);
                        }
                    } else if(d.name.indexOf('superiorList') === 0) {
                        var index = d.name.substring(12);
                        if(reviewers[index].type == 'superiorList') { // 连续上级
                          reviewers[index].superiorList = d.value;
                        }
                    } else if(d.name.indexOf('users') === 0) { // 指定人员
                        var index = d.name.substring(5);
                        if(reviewers[index].type == 'appointee') {
                          reviewers[index].users.push(d.value);
                        }
                    } else if(d.name.indexOf('rangeuser') === 0) { // 人员范围
                        var index = d.name.substring(9);
                        if(reviewers[index].type == 'select') {
                            reviewers[index].users.push(d.value);
                        }
                    } else if(d.name.indexOf('rangerole') === 0) { // 角色范围
                        var index = d.name.substring(9);
                        if(reviewers[index].type == 'select') {
                            reviewers[index].roles.push(d.value);
                        }
                    } else if(d.name.indexOf('rangeposition') === 0) { // 职位范围
                        var index = d.name.substring(13);
                        if(reviewers[index].type == 'select') {
                            reviewers[index].positions.push(d.value);
                        }
                    } else if(d.name.indexOf('rangedept') === 0) { // 部门范围
                        var index = d.name.substring(9);
                        if(reviewers[index].type == 'select') {
                            reviewers[index].depts.push(d.value);
                        }
                    } else if(d.name.indexOf('userRange') === 0) { // 指定范围
                        var index = d.name.substring(9);
                        if(reviewers[index].type == 'select') reviewers[index].userRange = d.value;
                    } else if(d.name == 'needAll') {
                        data.needAll = 1;
                    } else if(d.name == 'percent') {
                        data.percent = d.value;
                    } else if(d.name == 'multiple') {
                        data.multiple = d.value;
                    } else if(d.name == 'commentType') {
                        data.commentType = d.value;
                    } else if(d.name == 'agentType') {
                        data.agentType = d.value;
                    } else if(d.name == 'agentUser') {
                        if(data.agentType == 'appointee') {
                            data.agentUser = d.value;
                        }
                    } else if(d.name == 'deletedType') {
                        data.deletedType = d.value;
                    } else if(d.name == 'setUser') {
                        if(data.deletedType == 'setUser') {
                            data.setUser = d.value;
                        }
                    } else if(d.name == 'selfType') {
                        data.selfType = d.value;
                    }
                }
            }

            if(data.reviewType != 'reject') {
                if (d.name.indexOf('ccType') === 0) { // 设置抄送
                    var index = d.name.substring(6);
                    ccs[index] = {type: d.value};   // 支持指定人员，发起人自选，上级，角色
                    if(d.value == 'select') {
                        ccs[index].users     = [];
                        ccs[index].roles     = [];
                        ccs[index].depts     = [];
                        ccs[index].positions = [];
                    } else if(d.value == 'appointee') {    // 指定人员
                        ccs[index].users = [];
                    } else if(d.value == 'role') {  // 角色
                        ccs[index].roles = [];
                    } else if(d.value == 'position') {  // 角色
                        ccs[index].positions = [];
                    }
                } else if(d.name.indexOf('ccusers') === 0) { //设置抄送人
                    var index = d.name.substring(7);
                    if(ccs[index].type == 'appointee') {
                        ccs[index].users.push(d.value);
                    }
                } else if(d.name.indexOf('ccroles') === 0) { //设置抄送角色
                    var index = d.name.substring(7);
                    if(ccs[index].type == 'role') {
                        ccs[index].roles.push(d.value);
                    }
                } else if(d.name.indexOf('ccpositions') === 0) { //设置抄送职位
                    var index = d.name.substring(11);
                    if(ccs[index].type == 'position') {
                        ccs[index].positions.push(d.value);
                    }
                } else if(d.name.indexOf('ccrangeuser') === 0) { // 人员范围
                    var index = d.name.substring(11);
                    if(ccs[index].type == 'select') {
                        ccs[index].users.push(d.value);
                    }
                } else if(d.name.indexOf('ccrangerole') === 0) { // 角色范围
                    var index = d.name.substring(11);
                    if(ccs[index].type == 'select') {
                        ccs[index].roles.push(d.value);
                    }
                } else if(d.name.indexOf('ccrangeposition') === 0) { // 职位范围
                    var index = d.name.substring(15);
                    if(ccs[index].type == 'select') {
                        ccs[index].positions.push(d.value);
                    }
                } else if(d.name.indexOf('ccrangedept') === 0) { // 部门范围
                    var index = d.name.substring(11);
                    if(ccs[index].type == 'select') {
                        ccs[index].depts.push(d.value);
                    }
                } else if(d.name.indexOf('ccuserRange') === 0) { // 指定范围
                    var index = d.name.substring(11);
                    if(ccs[index].type == 'select') ccs[index].userRange = d.value;
                }
            }
        }

        if(node.type == 'approval') {
            data.reviewers = [];
            for(var i in reviewers) {
                data.reviewers.push(reviewers[i]);
            }
        }

        data.ccs = [];
        for(var i in ccs) {
            data.ccs.push(ccs[i]);
        }
        var message = checkNode(node.type, data)
        if(message) {
            new $.zui.Messager(message, { close: false }).show();
            return;
        }
        father[index1] = data;
        modal.close();

        render(nodes);
    })
}

function checkNode(type, data) {
    var reviewerSelectCount = 0;
    if(typeof data.reviewers === 'undefined' || data.reviewers.length == 0) {
        if(type == 'approval' && data.reviewType == 'manual') {
            return warningLang['needReviewer'];
        }
    } else {
        for(var reviewer of data.reviewers) {
            if(reviewer.type == 'appointee' && reviewer.users.length === 0) {
                return warningLang['selectUser'];
            } else if(reviewer.type == 'role' && reviewer.roles.length === 0) {
                return warningLang['selectRole'];
            } else if(reviewer.type == 'position' && reviewer.positions.length === 0) {
                return warningLang['selectPosition'];
            } else if(reviewer.type == 'select' || reviewer.type == 'setByPrev') {
                if(reviewerSelectCount !== 0) return warningLang['oneSelect'];
                reviewerSelectCount++;
            }
        }
    }

    var ccSelectCount = 0;
    if(typeof data.ccs !== 'undefined') {
        for(var cc of data.ccs) {
            if(cc.type == 'appointee' && cc.users.length === 0) {
                return warningLang['selectUser'];
            } else if(cc.type == 'role' && cc.roles.length === 0) {
                return warningLang['selectRole'];
            } else if(cc.type == 'position' && cc.positions.length === 0) {
                return warningLang['selectPosition'];
            } else if(cc.type == 'select') {
                if(ccSelectCount !== 0) return warningLang['oneSelect'];
                ccSelectCount++;
            }
        }
    }

    return '';
}

function renderNodes(nodes) {
    var nodesText = '';
    for(let index in nodes) {
        var node = nodes[index];
        if(node.type == 'branch') {
            nodesText += renderBranch(node);
        } else {
            nodesText += renderNode(node);
        }
    }

    return nodesText;
}

function renderConditions(conditions) {
    var conditionText = '';
    var conditionTitleText = '';
    var conditionNum  = 0;
    for(let index in conditions) {
        conditionNum++;
        var condition = conditions[index];
        if(condition.selectType == 'account' && condition.users.length > 0) {
            var userConditionText = '';
            for(let userIndex in condition.users) {
                userConditionText += users.hasOwnProperty(condition.users[userIndex]) ? users[condition.users[userIndex]] + '、' : '';
            }
            if(conditionNum <= 2) conditionText += conditionTextLang.user + userConditionText.slice(0, -1) + '<br>';
            conditionTitleText += conditionTextLang.user + userConditionText.slice(0, -1);
        }

        if(condition.selectType == 'dept') {
            var deptConditionText = '';
            for(let deptIndex in condition.depts) {
                deptConditionText += depts.hasOwnProperty(condition.depts[deptIndex]) ? depts[condition.depts[deptIndex]] + '、' : '';
            }
            if(conditionNum <= 2) conditionText += conditionTextLang.dept + deptConditionText.slice(0, -1) + '<br>';
            conditionTitleText += conditionTextLang.dept + deptConditionText.slice(0, -1);
        }

        if(condition.selectType == 'role') {
            var roleConditionText = '';
            for(let roleIndex in condition.roles) {
                roleConditionText += roles.hasOwnProperty(condition.roles[roleIndex]) ? roles[condition.roles[roleIndex]] + '、' : '';
            }
            if(conditionNum <= 2) conditionText += conditionTextLang.role + roleConditionText.slice(0, -1) + '<br>';
            conditionTitleText += conditionTextLang.role + roleConditionText.slice(0, -1);
        }

        if(condition.selectType == 'position') {
            var positionConditionText = '';
            for(let positionIndex in condition.positions) {
                positionConditionText += positions.hasOwnProperty(condition.positions[positionIndex]) ? positions[condition.positions[positionIndex]] + '、' : '';
            }
            if(conditionNum <= 2) conditionText += conditionTextLang.position + positionConditionText.slice(0, -1) + '<br>';
            conditionTitleText += conditionTextLang.position + positionConditionText.slice(0, -1);
        }

        if(conditionNum == 3) conditionText += '...';

        conditionTitleText += '&#10;';
    }

    var returnData = {
        text : conditionText,
        titleText : conditionTitleText.slice(0, -5),
    };
    return returnData;
}

function renderBranch(branch) {
    var branchType = branch.branchType == 'parallel' ? 'addParallel' : 'addCondition';
    var branchesText = '<div data-id="' + branch.id + '" class="editor-node route"><div class="top-h-line"></div><div class="add-condition"><div class="add-condition-inner">' + noticeTypeLang[branchType] + '<i class="iconfont-approval-admin icon-arrow"></i></div></div>';

    var branches = '';

    /* Branches. */
    for(var index in branch.branches) {
        var nodes      = branch.branches[index].nodes;
        var conditions = branch.branches[index].conditions;
        var conditionObj = renderConditions(conditions);
        branches +=
            '<div data-id=' + branch.branches[index].id + ' class="editor-node branch">' +
              '<div class="top-line-mask"></div>' +
              '<div class="top-v-line"></div>' +
              '<div class="nodes">' +
                '<div class="editor-node condition">' +
                  '<div class="editor-node-container">' +
                    '<div class="node-title node-title-condition">' +
                      '<span class="node-title-name">' + nodeTypeLang['condition'] + ' ' + (parseInt(index)+1) + '</span>' +
                      '<div class="node-btns">' +
                        '<button type="button" class="delete-btn operate-icon"><span class="universe-icon"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.0001 10.5858L19.7176 2.86825C19.9129 2.67299 20.2295 2.67299 20.4247 2.86825L21.1318 3.57536C21.3271 3.77062 21.3271 4.0872 21.1318 4.28246L13.4143 12L21.1318 19.7175C21.3271 19.9128 21.3271 20.2293 21.1318 20.4246L20.4247 21.1317C20.2295 21.327 19.9129 21.327 19.7176 21.1317L12.0001 13.4142L4.28258 21.1317C4.08732 21.327 3.77074 21.327 3.57548 21.1317L2.86837 20.4246C2.67311 20.2293 2.67311 19.9128 2.86837 19.7175L10.5859 12L2.86837 4.28246C2.67311 4.0872 2.67311 3.77062 2.86837 3.57536L3.57548 2.86825C3.77074 2.67299 4.08732 2.67299 4.28258 2.86825L12.0001 10.5858Z" fill="currentColor"></path></svg></span></button>' +
                      '</div>' +
                    '</div>' +
                    '<div class="node-content node-content-placeholder" data-title="' + conditionObj.titleText + '" title="' + conditionObj.titleText  + '"><div class="node-detail"><div class="node-detail-item"><div class="text-ellipsis">' + conditionObj.text + '</div></div></div><i class="iconfont-approval-admin icon-arrow"></i></div>' +
                  '</div>' +
                  '<div class="bottom-v-line"></div>' +
                  '<div class="add-node-btn"><div class="add-btn" style="z-index: auto;"><i class="add-btn-icon iconfont-approval-admin icon-plus"></i></div></div>' +
                '</div>';

        branches += renderNodes(nodes);
        branches += '</div><div class="bottom-v-line"></div><div class="bottom-line-mask"></div></div>';
    }

    /* Default. */
    branches += '<div data-id="' + branch.default.id + '" class="editor-node branch"><div class="top-line-mask"></div><div class="top-v-line"></div><div class="nodes"><div class="editor-node condition"><div class="editor-node-container" data-condition-id="CONDITION_648325_5857434" data-node-id="l10f2gm2-004"><div class="node-title node-title-condition"><span class="node-title-name">' + nodeTypeLang[branch.branchType == 'parallel' ? 'default' : 'other'] + '</span></div><div class="node-content"><div class="node-detail"><div class="node-detail-item"><div>' + noticeTypeLang[branch.branchType == 'condition' ? 'otherBranch' : 'defaultBranch'] + '</div></div></div><i class="iconfont-approval-admin icon-arrow"></i></div></div><div class="bottom-v-line"></div><div class="add-node-btn"><div class="add-btn" style="z-index: auto;"><i class="add-btn-icon iconfont-approval-admin icon-plus"></i></div></div></div>';
    branches += renderNodes(branch.default.nodes);
    branches += '</div><div class="bottom-v-line"></div><div class="bottom-line-mask"></div></div>';

    branches += '</div>';

    /* Bottom. */
    branches += '<div class="bottom-h-line"></div><div class="bottom-v-line"></div><div class="add-node-btn"><div class="add-btn" style="z-index: auto;"><i class="add-btn-icon iconfont-approval-admin icon-plus"></i></div></div>';

    branchesText +='<div class="branches">' + branches + '</div>';

    return branchesText;
}

function renderNode(node) {
    var notice = '';
    var id = '';
    var title = '';
    switch(node.type) {
        case 'start':
        case 'end':
            title = noticeTypeLang['setCC'];
            if(typeof node.ccs != 'undefined' && typeof node.ccs[0] != 'undefined' && typeof node.ccs[0].type != 'undefined') {
                for(let cc of node.ccs ) {
                    notice = reviewerTypeLang[cc.type].name + ' ';
                }
            } else {
                notice = noticeTypeLang['setCC'];
            }
            id = node.type;
            break;
        case 'approval':
            if(node.reviewType == 'manual' || typeof node.reviewType === 'undefined') {
                for(let reviewer of node.reviewers) {
                    notice = reviewerTypeLang[reviewer.type].name + ' ';
                }
            } else {
              notice = reviewTypeLang[node.reviewType];
            }
            id = node.id;

            title = noticeTypeLang['setReviewer'];
            break;
        case 'cc':
            for(let cc of node.ccs ) {
                notice = reviewerTypeLang[cc.type].name + ' ';
            }
            id = node.id;

            title = noticeTypeLang['setCC'];
            break;
    }

    $node = '<div data-id="' + id + '" class="editor-node ' + node.type + '"><div class="editor-node-container"><div class="node-title"><div class="node-title-name text-ellipsis" title="' + (typeof node.title === 'undefined' ? nodeTypeLang[node.type] : node.title) + '">' + (typeof node.title === 'undefined' ? nodeTypeLang[node.type] : node.title) + '</div></div><div class="node-content" data-title="' + title + '"><div class="node-detail"><div class="node-detail-item">' + notice + '</div></div></div>';
    if(node.type != 'start' && node.type != 'end') $node += '<i class="delete-btn iconfont-approval-admin icon-close"></i>';
    $node += '</div>';
    if(node.type != 'end') $node += '<div class="bottom-v-line"></div><div class="add-node-btn"><div class="add-btn" style="z-index: auto;"><i class="add-btn-icon iconfont-approval-admin icon-plus"></i></div></div>';
    $node += '</div>';

    return $node;
}

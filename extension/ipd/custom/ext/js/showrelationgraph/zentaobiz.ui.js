zui.Graph.loadModule().then(
function()
{
    const g6 = zui.Graph.Module;
    const cardWidth     = 300;
    const cardHeight    = 62;
    const relationWidth = 60;
    g6.registerNode(
      'tree-node',
      {
        drawShape: function drawShape(cfg, group) {
            const shape = group.addShape('rect', {
              attrs: {
                width: relationWidth,
                height: cardHeight,
                radius: 4
              },
              name: 'rect-shape'
            });

            if(cfg.nodeType == 'relation')
            {
                /* Relation DOM. */
                group.addShape('dom', {
                  attrs: {
                      y: 20,
                      width: relationWidth,
                      height: 20,
                      'html' : `
                      <div class="bg-primary-50 text-center">
                        <div class='text-primary px-1' title='${cfg.text}'>${cfg.text}</div>
                      </div>`
                  },
                  name: 'relation-dom'
                });
                group.addShape('marker', {
                  attrs: {
                    x: relationWidth,
                    y: 30,
                    r: 6,
                    cursor: 'pointer',
                    symbol: cfg.collapsed ? G6.Marker.expand : G6.Marker.collapse,
                    stroke: '#666',
                    lineWidth: 1,
                    fill: '#fff'
                  },
                  // must be assigned in G6 3.3 and later versions. it can be any string you want, but should be unique in a custom item type
                  name: 'collapse-icon',
                });
            }
            else
            {
                shape.attr({
                    width: cardWidth,
                    height: cardHeight,
                    stroke: '#d2d6e5',
                    class: 'graph-card',
                    fill: '#ffffff'
                });

                /* Title DOM. */
                let url = typeof(cfg.objectURL) != 'undefined' ? cfg.objectURL : '';
                if(cfg.objectType == 'release' || cfg.objectType == 'build' || cfg.objectType == 'mr') url = '';
                const title = cfg.id !== 'main' && url.length > 0 ? '<a class="item-title" href="' + url + '" data-toggle="modal" data-size="lg" title="' + cfg.objectTitle + '">#' + cfg.objectID + ' ' + cfg.objectTitle + '</a>' : '#' + cfg.objectID + ' ' + cfg.objectTitle;
                group.addShape('dom', {
                  attrs: {
                      width: cardWidth,
                      height: 29,
                      class: 'graph-card',
                      'html' : `
                      <div class='p-1.5 objectBox' data-objecttype='${cfg.objectType}' data-objectid='${cfg.objectID}'>
                        <span class="label label-id gray-300-outline size-sm rounded-full flex-none text-clip">${cfg.objectTypeName}</span>
                        ${title}
                      </div>`
                  },
                  name: 'title-dom'
                });

                /* Status DOM. */
                const objectStatus = cfg.objectStatus ? cfg.objectStatus : '';
                const statusName   = cfg.statusName   ? cfg.statusName   : '';
                group.addShape('dom', {
                    attrs: {
                        x: 0,
                        y: 30,
                        width: 100,
                        height: 25,
                        class: 'graph-card',
                        'html' : `
                        <div class='p-2'>
                          <span class="status-${objectStatus}">${statusName}</span>
                        </div>`
                    },
                    name: 'status-dom'
                });

                /* Avatar DOM. */
                if(cfg.objectAssign)
                {
                    const $assignedToAvatar = cfg.objectAssign.length ? zui.UserAvatar.renderHTML({account: cfg.objectAssign, src: usersAvatar[cfg.objectAssign], size: 20}) : '';
                    group.addShape('dom', {
                        attrs: {
                            x: 270,
                            y: 35,
                            width: 20,
                            height: 20,
                            class: 'graph-card',
                            'html' : `${$assignedToAvatar}`
                        },
                        name: 'avatar-dom'
                    });
                }
            }
          return shape;
        },
        setState(name, value, item)
        {
            if(name === 'collapsed')
            {
                const marker = item.get('group').find((ele) => ele.get('name') === 'collapse-icon');
                const icon   = value ? G6.Marker.expand : G6.Marker.collapse;
                marker.attr('symbol', icon);
            }
        },
      },
      'single-node'
    );
});

window.getVGap = function(d)
{
    return 25;
}
window.getHGap = function(d)
{
    if(d.nodeType == "relation") return 80;
    return 200;
}
window.getSide = function()
{
    return 'right';
}

waitDom('#zin_graph_' + graphID, function()
{
    let $this = $(this);
    setTimeout(function()
    {
        let graph = $this.zui('Graph').instance;
        graph.on('node:click', function(e)
        {
            const node = e.item;
            if(!node) return;

            const model = node.getModel();
            if(e.target.get('name') === 'collapse-icon')
            {
                model.collapsed = !model.collapsed;
                graph.setItemState(node, 'collapsed', model.collapsed);
                graph.layout();
            }
        });
    }, 500);
});

$(document).off('click', 'path.graph-card,.graph-card :not(a)').on('click', 'path.graph-card,.graph-card :not(a)', function()
{
    let $this = $(this);
    if(!$this.hasClass('objectBox')) $this = $this.closest('g').find('.objectBox');
    if($this.length == 0) return;

    let objectID   = $this.data('objectid');
    let objectType = $this.data('objecttype');
    if(objectType == currentObjectType && objectID == currentObjectID) return;
    loadModal($.createLink('custom', 'showRelationGraph', 'objectID=' + objectID + '&objectType=' + objectType));
});

$(document).off('mouseenter', '.graph-card').on('mouseover', '.graph-card', function()
{
    $(this).closest('g').find('path.graph-card').attr('fill', '#f5f5f5');
});

$(document).off('mouseleave', '.graph-card').on('mouseout', '.graph-card', function()
{
    $(this).closest('g').find('path.graph-card').attr('fill', '#ffffff');
});

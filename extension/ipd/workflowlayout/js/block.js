$(document).ready(function()
{
    $('.blockList').sortable({trigger: '.sort-block-handler', selector: 'li', dragCssClass: ''});
    $('.blockList .tabList').sortable({trigger: '.sort-tab-handler', selector: 'li', dragCssClass: ''});
})

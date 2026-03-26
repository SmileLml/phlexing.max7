<script>
$('a[disabled=disabled]').addClass('disabled');

$(".select-action").each(function(){$(this).attr("title",$(this).text());});

var html = $('#actionListTable tr td.actions a.edit:first').html();
$('#actionListTable tr td.actions a.edit').attr('title', html).addClass('btn').html("<i class='icon icon-edit'></i>");

var html = $('#actionListTable tr td.actions a.layout:first').html();
$('#actionListTable tr td.actions a.layout').attr('title', html).addClass('btn').html("<i class='icon icon-layout'></i>");

var html = $('#actionListTable tr td.actions a.condition:first').html();
$('#actionListTable tr td.actions a.condition').attr('title', html).addClass('btn').html("<i class='icon icon-trigger'></i>");

var html = $('#actionListTable tr td.actions a.moreActions:first').text();
$('#actionListTable tr td.actions a.moreActions').attr('title', html).addClass('btn').html("<i class='icon icon-more-circle'></i><span class='caret'></span>");
</script>

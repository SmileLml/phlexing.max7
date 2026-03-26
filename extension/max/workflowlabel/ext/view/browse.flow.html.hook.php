<script>
$('a[disabled=disabled]').addClass('disabled');

var html = $('#labelList tr td.actions a.edit:first').html();
$('#labelList tr td.actions a.edit').attr('title', html).addClass('btn').html("<i class='icon icon-edit'></i>");

var html = $('#labelList tr td.actions a.deleter:first').html();
$('#labelList tr td.actions a.deleter').attr('title', html).addClass('btn').html("<i class='icon icon-trash'></i>");
</script>

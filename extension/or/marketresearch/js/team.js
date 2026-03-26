/**
 * Delete memeber of research team.
 *
 * @param  researchID $researchID
 * @param  account    $account
 * @param  userID     $userID
 * @access public
 * @return void
 */
function deleteMemeber(researchID, account, userID)
{
    bootbox.confirm(confirmUnlinkMember, function(result)
    {
        if(!result) return true;

        var unlinkURL = createLink('marketresearch', 'unlinkMember', 'researchID=' + researchID + '&userID=' + userID + '&confirm=yes');
        unlinkMember(unlinkURL);
    })
}

/**
 * Unlink member from project.
 *
 * @param  unlinkURL $unlinkURL
 * @access public
 * @return void
 */
function unlinkMember(unlinkURL)
{
    $.get(unlinkURL, function(data)
    {
        data = JSON.parse(data);
        if(data.result == 'success') window.location.reload();
    });
}

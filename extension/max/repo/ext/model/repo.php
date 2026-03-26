<?php
/**
 * Get review.
 *
 * @param  int    $repoID
 * @param  string $entry
 * @param  string $revision
 * @access public
 * @return array
 */
public function getReview($repoID, $entry, $revision)
{
    return $this->loadExtension('repo')->getReview($repoID, $entry, $revision);
}

/**
 * Get bugs by repo.
 *
 * @param  int    $repoID
 * @param  string $browseType
 * @param  int    $executionID
 * @param  array  $bugs
 * @param  string $orderBy
 * @param  object $pager
 * @access public
 * @return array
 */
public function getBugsByRepo($repoID, $browseType = '', $executionID = 0, $bugs = array(), $orderBy = 'id_desc', $pager = null)
{
    return $this->loadExtension('repo')->getBugsByRepo($repoID, $browseType, $executionID, $bugs, $orderBy, $pager);
}

/**
 * Save bug.
 *
 * @param  int    $repoID
 * @param  object $bug
 * @access public
 * @return array
 */
public function saveBug($repoID, $bug)
{
    return $this->loadExtension('repo')->savebug($repoID, $bug);
}

/**
 * Update bug.
 *
 * @param  int    $bugID
 * @param  string $title
 * @access public
 * @return string
 */
public function updateBug($bugID, $title)
{
    return $this->loadExtension('repo')->updateBug($bugID, $title);
}

/**
 * Update comment.
 *
 * @param  int    $commentID
 * @param  string $comment
 * @access public
 * @return string
 */
public function updateComment($commentID, $comment)
{
    return $this->loadExtension('repo')->updateComment($commentID, $comment);
}

/**
 * Delete comment.
 *
 * @param  int    $commentID
 * @access public
 * @return void
 */
public function deleteComment($commentID)
{
    return $this->loadExtension('repo')->deleteComment($commentID);
}

/**
 * Get last review info.
 *
 * @param  string $entry
 * @access public
 * @return object
 */
public function getLastReviewInfo($entry)
{
    return $this->loadExtension('repo')->getLastReviewInfo($entry);
}

/**
 * Get linked objects by comment.
 *
 * @param  string    $comment
 * @access public
 * @return array
 */
public function getLinkedObjects($comment)
{
    return $this->loadExtension('repo')->getLinkedObjects($comment);
}

/**
 * Get diff file tree.
 *
 * @param  string $diffs
 * @access public
 * @return object
 */
public function getDiffFileTree($diffs)
{
    return $this->loadExtension('repo')->getDiffFileTree($diffs);
}

/**
 * Get review comments.
 *
 * @param  array  $bugIDList
 * @access public
 * @return array
 */
public function getComments($bugIDList)
{
    return $this->loadExtension('repo')->getComments($bugIDList);
}

{*<!--
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
{if !empty($CHILD_COMMENTS_MODEL)}
<ul class="liStyleNone">
	{foreach item=COMMENT from=$CHILD_COMMENTS_MODEL}
		<li class="commentDetails">
		{include file='CommentThreadList.tpl'|@vtemplate_path COMMENT=$COMMENT}
		{assign var=CHILD_COMMENTS value=$COMMENT->getChildComments()}
		{if !empty($CHILD_COMMENTS)}
			{include file='CommentsListIteration.tpl'|@vtemplate_path CHILD_COMMENTS_MODEL=$COMMENT->getChildComments()}
		{/if}
		</li>
		<br>
	{/foreach}
</ul>
{/if}
{/strip}
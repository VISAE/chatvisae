<?php
/*
 * Copyright 2005-2013 the original author or authors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once("inc_menu.php");
$page['title'] = getlocal("page_analysis.search.title");
$page['menuid'] = "history";

function tpl_content() { global $page, $mibewroot;
?>

<?php echo getlocal("page_search.intro") ?>
<br />
<br />

<form name="searchForm" method="get" action="<?php echo $mibewroot ?>/operator/history.php">
	<div class="mform"><div class="formtop"><div class="formtopi"></div></div><div class="forminner">
	
	<div class="fieldForm">
		<div class="field">
			<div class="flabel"><?php echo getlocal("page_analysis.full.text.search") ?></div>
			<div class="fvaluenodesc">
				<div id="searchtext">
					<input type="text" name="q" size="80" value="<?php echo form_value('q') ?>" class="formauth"/>
				</div>
				<div id="searchbutton">
					<input type="image" name="search" src="<?php echo $mibewroot . safe_htmlspecialchars(getlocal("image.button.search")) ?>" alt="<?php echo safe_htmlspecialchars(getlocal("button.search")) ?>"/>
				</div>
			</div>
			<br clear="all"/>
		</div>
	</div>

	</div><div class="formbottom"><div class="formbottomi"></div></div></div>
</form>
<br/>


<?php if( $page['pagination'] ) { ?>

<table class="list">
<thead>
<tr class="header">
<th>
	<?php echo getlocal("page.analysis.search.head_name") ?>
</th><th>
	<?php echo getlocal("page.analysis.search.head_host") ?>
</th><th>
	<?php echo getlocal("page.analysis.search.head_operator") ?>
</th><th>
	<?php echo getlocal("page.analysis.search.head_messages") ?>
</th><th>
	<?php echo getlocal("page.analysis.search.head_time") ?>
</th></tr>
</thead>
<tbody>
<?php
if( $page['pagination.items'] ) {
	foreach( $page['pagination.items'] as $chatthread ) { ?>
	<tr>
		<td>
			<a href="<?php echo $mibewroot ?>/operator/threadprocessor.php?threadid=<?php echo urlencode($chatthread['threadid']) ?>" target="_blank" onclick="this.newWindow = window.open('<?php echo $mibewroot ?>/operator/threadprocessor.php?threadid=<?php echo urlencode($chatthread['threadid']) ?>', '', 'toolbar=0,scrollbars=1,location=0,status=1,menubar=0,width=720,height=520,resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;"><?php echo topage(safe_htmlspecialchars($chatthread['userName'])) ?></a>
		</td>
		<td>
		<?php echo get_user_addr(topage($chatthread['remote'])) ?>
		</td>
		<td>
		<?php if( $chatthread['agentName'] ) {
			echo topage(safe_htmlspecialchars($chatthread['agentName']));
		} else if($chatthread['groupid'] && $chatthread['groupid'] != 0 && isset($page['groupName'][$chatthread['groupid']])) {
			echo "- ".topage(safe_htmlspecialchars($page['groupName'][$chatthread['groupid']]))." -";
		}
		?>
		</td>
		<td>
		<?php echo topage(safe_htmlspecialchars($chatthread['size'])) ?>
		</td>
		<td>
			<?php echo date_diff_to_text($chatthread['modified']-$chatthread['created']) ?>, <?php echo date_to_text($chatthread['created']) ?>
		</td>
	</tr>
<?php
	}
} else {
?>
	<tr>
	<td colspan="5">
		<?php echo getlocal("tag.pagination.no_items") ?>
	</td>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php
	if( $page['pagination.items'] ) {
		echo "<br/>";
		echo generate_pagination($page['pagination']);
	}
}
?>

<?php
} /* content */

require_once('inc_main.php');
?>
<?php

/**
 * View for plugin settings panel.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="spiderblocker-root">
  <h1><?php esc_html_e( 'Spider Blocker', 'spiderblocker' ); ?></h1>
  <hr/>

  <div ng-app="spiderBlockApp">
	<div ng-controller="NotificationsCtrl">
	  <div class="notice notice-{{ n.state }} fixed" ng-repeat="n in notifications" style="top: {{3.5*($index+1)}}em">
		<p>{{n.msg}}
		  <a ng-click="removeNotification(notification)">
			<span class="dashicons dashicons-no-alt"></span>
		  </a>
		</p>
	  </div>
	</div>

	<div ng-controller="BotListCtrl">
	  <h2><?php esc_html_e( 'Add New Bot', 'spiderblocker' ); ?></h2>

	  <form name="add_form" ng-submit="add()">
		<table class="form-table">
		  <tbody>
			<tr>
			  <th scope="row"><label><?php esc_html_e( 'User Agent', 'spiderblocker' ); ?></label></th>
			  <td><input type="text" bots="bots" ng-model='bot.re' class="regular-text" required/></td>
			</tr>
			<tr>
			  <th scope="row"><label><?php esc_html_e( 'Bot Name', 'spiderblocker' ); ?></label></th>
			  <td><input type="text" ng-model='bot.name' class="regular-text" required/></td>
			</tr>
			<tr>
			<tr>
			  <th scope="row"><label><?php esc_html_e( 'Bot Description URL', 'spiderblocker' ); ?></label></th>
			  <td><input type="url" ng-model='bot.desc' class="regular-text" placeholder="http://"/>
			  </td>
			</tr>
		  </tbody>
		</table>

		<p class="submit">
		  <input ng-disabled="add_form.$invalid" type="submit" class="button button-primary" value="<?php esc_attr_e( 'Add Bot', 'spiderblocker' ); ?>">
		</p>
	  </form>

	  <div class="sb-table-top">
		<h2><?php esc_html_e( 'List of bots', 'spiderblocker' ); ?></h2>

		<ng-form class="search-box">
		  <input size="35" type="search" id="ua-search-input" ng-model="query" placeholder="<?php esc_attr_e( 'Filter...', 'spiderblocker' ); ?>">
		</ng-form>
	  </div><!-- .sb-table-top -->

	  <table class="wp-list-table widefat bots">
		<thead>
		  <tr>
			<th scope="col" class="manage-column column-description">
			  <a href="" ng-click="predicate = 're'; reverse=false">
				<?php esc_html_e( 'User Agent', 'spiderblocker' ); ?> <span class="dashicons dashicons-sort"></span>
			  </a>
			</th>
			<th scope="col" class="manage-column column-name">
			  <?php esc_html_e( 'Name', 'spiderblocker' ); ?>
			</th>
			<th scope="col" class="manage-column column-state">
			  <a href="" ng-click="predicate = 'state'; reverse=false">
				<?php esc_html_e( 'State', 'spiderblocker' ); ?> <span class="dashicons dashicons-sort"></span>
			  </a>
			</th>
			<th scope="col" id="action" class="manage-column column-action">
			  <?php esc_html_e( 'Action', 'spiderblocker' ); ?>
			</th>
		  </tr>
		</thead>

		<tfoot>
		  <tr>
			<th scope="col" class="manage-column column-description">
			  <a href="" ng-click="predicate = 're'; reverse=false">
				<?php esc_html_e( 'User Agent', 'spiderblocker' ); ?>
			  </a>
			</th>
			<th scope="col" class="manage-column column-name">
			  <?php esc_html_e( 'Name', 'spiderblocker' ); ?>
			</th>
			<th scope="col" class="manage-column column-state">
			  <a href="" ng-click="predicate = 'state'; reverse=false">
				<?php esc_html_e( 'State', 'spiderblocker' ); ?>
			  </a>
			</th>
			<th scope="col" id="action" class="manage-column column-action">
			  <?php esc_html_e( 'Action', 'spiderblocker' ); ?>
			</th>
		  </tr>
		</tfoot>

		<tbody id="the-list">
		  <tr id="spider-blocker" ng-repeat="bot in bots | filter:query | orderBy:predicate:reverse"
			ng-class="{'active': bot.state}">

			<th class="bot-re"> {{ bot.re }}</th>
			<td class="bot-title"><strong>{{ bot.name }}</strong> <a target="_blank" ng-href="{{bot.desc}}">{{ bot.desc }}</a></td>
			<th class="expression" ng-class="{'blocked':bot.state}"> {{ bot.state?"Blocked":"Allowed" }}</th>
			<td class="actions">
			  <input ng-hide="bot.state" type="button" ng-click="bot.state=true" class="button button-primary" value="<?php esc_attr_e( 'Block', 'spiderblocker' ); ?>">
			  <input ng-show="bot.state" type="button" ng-click="bot.state=false" class="button button-secondary" value="<?php esc_attr_e( 'Allow', 'spiderblocker' ); ?>">
			  <input type="button" ng-click="remove(bot.re)" class="button button-secondary" value="<?php esc_attr_e( 'Remove', 'spiderblocker' ); ?>">
			</td>
		  </tr>
		</tbody>
	  </table>

	  <div id="rules-export-import" style="display:none;">
		<textarea style="-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;width: 100%;height: 99%;" json-text ng-model="bots"></textarea>
	  </div>

	  <p class="submit">
		<input type="button" class="button button-primary" ng-click="save()" value="<?php esc_attr_e( 'Save', 'spiderblocker' ); ?>">
		<input type="button" class="button button-primary" ng-click="reset()" value="<?php esc_attr_e( 'Reset to Defaults', 'spiderblocker' ); ?>">
		<a href="#TB_inline?width=540&height=360&inlineId=rules-export-import" class="thickbox button button-secondary"><?php esc_html_e( 'Import/Export Definitions', 'spiderblocker' ); ?></a>
	  </p>
	</div>
  </div>
</div><!-- .spiderblocker-root -->

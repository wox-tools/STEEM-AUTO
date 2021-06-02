<style>
	.action-icon{
		font-size: 28px;
		cursor: pointer;
	}
	.action-config-icon{
		color: blue;
	}
	.action-close-icon{
		color: red;
	}
</style>
<?php

if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] >0){
	$page = $_GET['p'];
}else{
	$page =0;
}
$mysqlpage = 20*$page;

if(isset($_GET['fan']) && $_GET['fan'] != ''){
	$searchfan = 1;
}else{
	$searchfan = 0;
}
?>

<!-- Settings for selected fans -->
<div class="modal fade" id="myModalselectedfans" role="dialog">
	<div class="modal-dialog">
	<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Settings for selected fans</h4>
			</div>
			<div class="modal-body">
				<!-- body -->
				<div style="text-align:left; display:; padding:20px;" id="setall" class="col-md-12">
					<form onsubmit="settingsforselectedfans(); return false;">
						<label>Settings for selected Fans:</label>
						<!-- voting weight -->
						<label>Weight: Default Weight is 100%. leave it empty to be default.</label>
						<input id="weightall" placeholder="Voting Weight" value="50" name="weight" type="number" class="form-control" step="0.01" min="0" max="100">
						<!-- wait time before upvoting -->
						<label for="aftermin">Time to wait before voting. Default Time is 0 minutes.</label>
						<input id="afterminall" placeholder="Upvoting After X Minutes." value="0" name="aftermin" type="number" class="form-control" step="1" min="0" max="30">
						<hr>
						<!-- daily limit -->
						<label for="dailylimit">Daily limit:<abbr data-toggle="tooltip" title="You will upvote only limited posts per 24 hours.">?</abbr></label>
						<input type="number" name="dailylimit" placeholder="Voting Weight" id="dailylimitall" class="form-control" value="2" min="1" max="99" step="1" required>
						<!-- enable/disable -->
						<li style="margin-top:5px; margin-bottom:5px;" class="list-group-item">
							Enabled:
							<div class="material-switch pull-right">
								<input id="enableall" name="enable" class="enable" type="checkbox" checked/>
								<label for="enableall" id="enable" class="label-success"></label>
							</div>
						</li>
						<input style="margin-top:10px;"value="Apply to selected fans" type="submit" class="btn btn-primary">
					</form>

				</div>
			</div>
			<div style="border-top:0;" class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /End settings for selected fans -->
<script type="text/javascript">
//Select/unselect all function
	function togglecheckbox(source) {
		var checkboxes = document.getElementsByName('fan_id');
		for(var i=0, n=checkboxes.length;i<n;i++) {
			checkboxes[i].checked = source.checked;
		}
	}
	function settingsforselectedfans(){ //settings for a fan
		$('.btn').attr('disabled','true');
		var minute = document.getElementById('afterminall').value;
		var weight = document.getElementById('weightall').value;
		var dailylimit = document.getElementById('dailylimitall').value;
		if(minute == '' || minute == null){
			minute = 0;
		}
		if(weight == '' || weight == null){
			weight = 100;
		}
		var enable;
		if(document.getElementById('enableall').checked){
			enable = 1;
		}else{
			enable = 0;
		}
		var checkboxes = document.getElementsByName('fan_id');
		for(var i=0, n=checkboxes.length;i<n;i++) {
			if(checkboxes[i].checked){
				var user = checkboxes[i].id;
				const body = 'fan=' + encodeURIComponent(user) +
					'&weight=' + encodeURIComponent(weight) +
					'&minute=' + encodeURIComponent(minute) +
					'&enable=' + encodeURIComponent(enable) +
					'&dailylimit=' + encodeURIComponent(dailylimit)

				callApi('api/v1/dashboard/fanbase/settings', body)
			}
		}
		return 1;
	}
	$(function(){
		$('#myModalselectedfans').appendTo('body');
	});
	function modalforselectedfans() {
		var checked = 0;
		var checkboxes = document.getElementsByName('fan_id');
		for(var i=0, n=checkboxes.length;i<n;i++) {
			if(checkboxes[i].checked){
				checked = 1;
			}
		}
		if(checked){
			$('[id=\'myModalselectedfans\']').modal('show');
		}else{
			$.notify({
				icon: 'pe-7s-attention',
				message: "Please select some fan from the list!"
			},{
				type: 'warning',
				timer: 1000
			});
		}
	}
</script>

<div class="content"> <!-- Content -->
<?php if($searchfan == 1){	?>
		<div class="row" style="margin:0 !important"> <!-- 2 -->
			<div class="col-md-3"></div>
				<div class="col-md-6"> <!-- 3 -->
					<div class="card"> <!-- 4 -->
						<div class="content"> <!-- 5 -->
							<h3>Searching for fan: </h3><br>
							<?php
							$stmt = $conn->prepare("SELECT EXISTS(SELECT * FROM `fans` WHERE `fan`=?)");
							$searchedfan = $_GET['fan'];
							$stmt->bind_param('s', $searchedfan);
							$stmt->execute();
							$result = $stmt->get_result();
							$row = $result->fetch_assoc();
							foreach($row as $exists){}
							if($exists == 1){
								$stmt = $conn->prepare("SELECT * FROM `fans` WHERE `fan`=?");
								$stmt->bind_param('s', $searchedfan);
								$stmt->execute();
								$result = $stmt->get_result();
								$row = $result->fetch_assoc();
								$resultt = $conn->query("SELECT EXISTS(SELECT * FROM `fanbase` WHERE `follower` = '$name' AND `fan`='$searchedfan')");
								foreach($resultt as $y){
									foreach($y as $y){}
								}
								if($y == 1){
									$alreadyfollowed = 1;
								}else{
									$alreadyfollowed = 0;
								}

								?>
								<strong>Fan name:</strong><span> <?php echo htmlspecialchars($searchedfan); ?></span><br>
								<strong>Followers:</strong><span> <?php echo $row['followers']; ?> (<a href="/dash.php?i=15&id=2&user=<?php echo htmlspecialchars($searchedfan); ?>">Show enable followers</a>)</span><br><br>
								<?php if($alreadyfollowed){ ?>
									<button onclick="if(confirm('Are you sure?')){unfollow1('<?php echo $row['fan']; ?>');};" class="btn btn-danger" <?php if($row['fan'] == $name){echo 'disabled="disabled"';} ?>>UNFOLLOW</button>
									<button onclick="showset('1');" class="btn btn-primary">Settings</button>
									<?php
									$resultt = $conn->query("SELECT * FROM `fanbase` WHERE `follower` = '$name' AND `fan`='$searchedfan'");
									foreach($resultt as $n){}
									?>
									<!-- Settings -->
									<div class="row" style="margin:0 !important;">
										<div style="text-align:left; display:none; padding:20px;" id="set1" class="">
											<form onsubmit="settings1('<?php echo $row['fan']; ?>'); return false;">
												<label>Settings for Fan: <a href="https://steemit.com/@<?php echo $row['fan']; ?>" target="_blank">@<?php echo $row['fan']; ?></a></label>
												<!-- voting weight -->
												<label>Weight: Default Weight is 100%. leave it empty to be default.</label>
												<input id="weight<?php echo $row['fan']; ?>" placeholder="Voting Weight" value="<?php echo $n['weight']/100; ?>" name="weight" type="number" class="form-control" step="0.01" min="0" max="100">
												<!-- wait time before upvoting -->
												<label>Time to wait before voting. Default Time is 0 minutes.</label>
												<input id="aftermin<?php echo $row['fan']; ?>" placeholder="Upvoting After X Minutes." value="<?php echo $n['aftermin']; ?>" name="aftermin" type="number" class="form-control" step="1" min="0" max="30">
												<hr>
												<!-- daily limit -->
												<label for="dailylimit">Daily limit:<abbr data-toggle="tooltip" title="You will upvote only limited posts per 24 hours.">?</abbr></label>
												<input type="number" name="dailylimit" placeholder="Voting Weight" id="dailylimit<?php echo $n['fan']; ?>" class="form-control" value="<?php echo $n['dailylimit'] ?>" min="1" max="99" step="1" required>
												<!-- enable/disable -->
												<li style="margin-top:5px; margin-bottom:5px;" class="list-group-item">
													Enabled:
													<div class="material-switch pull-right">
														<input id="enable<?php echo $row['fan']; ?>" name="enable" class="enable" type="checkbox" <?php if($n['enable'] == 1){echo 'checked';} ?>/>
														<label for="enable<?php echo $row['fan']; ?>" id="enable" class="label-success"></label>
													</div>
												</li>
												<input style="margin-top:10px;"value="Save Settings" type="submit" class="btn btn-primary">
											</form>
										</div>
										<div class="col-md-3"></div>
									</div>
									<!-- /Settings -->

								<?php }else{ ?>
										<button onclick="if(confirm('Are you sure?')){follow1('<?php echo $row['fan']; ?>');};" class="btn btn-primary" <?php if($row['fan'] == $name){echo 'disabled="disabled"';} ?>>FOLLOW</button>
								<?php } ?>



								<?php
							}else{ ?>
								<p style="color:red;">Can't find. First, someone should follow that fan.</p>
							<?php
							}
							?>
						</div> <!-- /5 -->
					</div> <!-- /4 -->
				</div> <!-- /3 -->
			<div class="col-md-3"></div>
		</div> <!-- /2 -->

		<?php
	}else{ ?>

	<div class="row" style="margin:0 !important;">
		<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="card">
					<div class="content">
						<h3>Welcome <?php echo $name; ?>,</h3><br>
						Here you can see a list of the most popular authors and upvote them.<br>						Follow someone to auto upvote that user's posts.
						<form style="display:;" id="become" onsubmit="follow2(); return false;">
							<label>Username:</label>
							<input id="userx" placeholder="For example: mahdiyari" name="username" type="text" class="form-control" required>
							<input style="margin-top:10px;"value="Follow" type="submit" class="btn btn-primary">
						</form>
					</div>
				</div>
			</div>
		<div class="col-md-3"></div>
	</div>

	<div class="row" style="margin:0 !important;"> <!-- Row -->
		<div class="col-md-12"> <!-- Col-12 -->
			<div class="card"><!-- card -->
				<div class="content"><!-- content -->
					<h3 style="border-bottom:1px solid #000; padding-bottom:10px;">
						You Are following:
						<button style="float:right;" type="button" class="btn btn-primary" onclick="modalforselectedfans();">Settings for selections</button>
					</h3>
					<div style="max-height:600px; overflow:auto;" class="table-responsive-vertical shadow-z-1">
						<?php
						$result = $conn->query("SELECT EXISTS(SELECT * FROM `fans`)");
						foreach($result as $x){
							foreach($x as $x){}
						}
						if($x == 0){
							echo 'None';
						}else{
							$result = $conn->query("SELECT EXISTS(SELECT * FROM `fanbase` WHERE `follower`= '$name')");
							foreach($result as $y){
								foreach($y as $y){}
							}
							if($y == 1){
								?>

								<!-- Table starts here -->
								<table id="table" class="table table-hover table-mc-light-blue">
									<thead>
										<tr>
											<th><input type="checkbox" name="" onclick="togglecheckbox(this);" value="" id="selectall"></th>
											<th>#</th>
											<th>Username</th>
											<th>Followers</th>
											<th>Weight</th>
											<th>Wait Time</th>
											<th>Daily Limit<abbr data-toggle="tooltip" title="You will upvote only limited posts per 24 hours.">?</abbr></th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

										<?php
										$result = $conn->query("SELECT * FROM `fanbase` WHERE `follower` = '$name'");
										$k = 1;
										$enb;
										foreach($result as $n){
											$nn = $n['fan'];
											if($n['enable'] == 1){
												$status = '<i style="color:green; font-size:24px;" title="Enabled" class="pe-7s-check"></i>';
												$enb = 1;
											}else{
												$status = '<i style="color:red; font-size:24px;" title="Disabled" class="pe-7s-less"></i>';
												$enb = 0;
											}
											$result = $conn->query("SELECT * FROM `fans` WHERE `fan` = '$nn'");
											foreach($result as $b){
												?>

												<tr class="tr1">
													<td data-title="ID"><input type="checkbox" name="fan_id" value="" id="<?php echo $b['fan']; ?>"></td>
													<td data-title="ID"><?php echo $k; ?></td>
													<td data-title="Name"><a href="/dash.php?i=2&fan=<?php echo $b['fan']; ?>" target="_blank">@<?php echo $b['fan']; ?></a></td>
													<td data-title="Status"><?php echo $b['followers']; ?></td>
													<td data-title="Status"><?php echo $n['weight']/100; ?>%</td>
													<td data-title="Status"><?php echo $n['aftermin']; ?> min</td>
													<td data-title="Status"><?php echo $n['dailylimit'] ?></td>
													<td data-title="Status"><?php echo $status ?></td>
													<td data-title="Status">
														<a title="Settings" data-toggle="modal" onclick="$('[id=\'myModal<?php echo $b['fan']; ?>\']').modal('show');" class="pe-7s-config action-icon action-config-icon"></a>
														<a title="Delete" onclick="if(confirm('Are you sure?')){unfollow1('<?php echo $b['fan']; ?>');};" class="pe-7s-close-circle action-icon action-close-icon"></a>
													</td>
												</tr>

												<!-- Settings -->
												<div class="modal fade" id="myModal<?php echo $b['fan']; ?>" role="dialog">
													<div class="modal-dialog">
													<!-- Modal content-->
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title">Settings: @<?php echo $b['fan']; ?></h4>
															</div>
															<div class="modal-body">
																<!-- body -->
																<div style="text-align:left; display:; padding:20px;" id="set<?php echo $k; ?>" class="col-md-12">
																	<form onsubmit="settings1('<?php echo $b['fan']; ?>'); return false;">
																		<label>Settings for Fan: <a href="https://steemit.com/@<?php echo $b['fan']; ?>" target="_blank">@<?php echo $b['fan']; ?></a></label>
																		<!-- voting weight -->
																		<label>Weight: Default Weight is 100%. leave it empty to be default.</label>
																		<input id="weight<?php echo $b['fan']; ?>" placeholder="Voting Weight" value="<?php echo $n['weight']/100; ?>" name="weight" type="number" class="form-control" step="0.01" min="0" max="100">
																		<!-- wait time before upvoting -->
																		<label for="aftermin">Time to wait before voting. Default Time is 0 minutes.</label>
																		<input id="aftermin<?php echo $b['fan']; ?>" placeholder="Upvoting After X Minutes." value="<?php echo $n['aftermin']; ?>" name="aftermin" type="number" class="form-control" step="1" min="0" max="30">
																		<hr>
																		<!-- daily limit -->
																		<label for="dailylimit">Daily limit:<abbr data-toggle="tooltip" title="You will upvote only limited posts per 24 hours.">?</abbr></label>
																		<input type="number" name="dailylimit" placeholder="Voting Weight" id="dailylimit<?php echo $b['fan']; ?>" class="form-control" value="<?php echo $n['dailylimit'] ?>" min="1" max="99" step="1" required>
																		<!-- enable/disable -->
																		<li style="margin-top:5px; margin-bottom:5px;" class="list-group-item">
																			Enabled:
																			<div class="material-switch pull-right">
																				<input id="enable<?php echo $b['fan']; ?>" name="enable" class="enable" type="checkbox" <?php if($enb == 1){echo 'checked';} ?>/>
																				<label for="enable<?php echo $b['fan']; ?>" id="enable" class="label-success"></label>
																			</div>
																		</li>
																		<input style="margin-top:10px;"value="Save Settings" type="submit" class="btn btn-primary">
																	</form>

																</div>
															</div>
															<div style="border-top:0;" class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</div>
												<script>
												$(document).ready(function(){
													$('[id="myModal<?php echo $b['fan']; ?>"').appendTo("body");
												});
												</script>
												<!-- /Settings -->

												<?php
												$k += 1;

											}
										}
										?>
									</tbody>
								</table>
								<?php
							}else{
								echo 'None.';
							}
						}
						?>
					</div>
				</div><!-- /contact -->
			</div><!-- /card -->
		</div><!-- /Col-12 -->
	</div><!-- /Row -->


	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div class="card">
				<div class="content">
					<h3>Search for a fan:</h3>
					<hr style="margin:10px;">
					<form action="/dash.php" class="form" method="GET">
						<label for="fan">Fan name:</label><input class="form-control" id="fan" placeholder="steemauto" name="fan" type="text" required/>
						<input name="i" type="number" value="2" style="display:none;" required>
						<input style="margin-top:7px;" class="btn btn-primary" value="Search" type="submit"/>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-3"></div>
	</div>


	<!-- -->
	<div class="row" style="margin:0 !important;"> <!-- Row -->
		<div class="col-md-12"> <!-- Col-12 -->
			<div class="card"><!-- card -->
				<div class="content"><!-- content -->
					<h3 style="border-bottom:1px solid #000; padding-bottom:10px;">Top Fans:</h3>
					<?php
					$result = $conn->query("SELECT EXISTS(SELECT * FROM `fans` ORDER BY `fans`.`followers` DESC LIMIT $mysqlpage,20)");
					foreach($result as $x){
						foreach($x as $x){}
					}
					if($x == 0){
						echo 'None';
					}else{
						$result = $conn->query("SELECT EXISTS(SELECT * FROM `fanbase` WHERE `follower` = '$name')");
						foreach($result as $y){
							foreach($y as $y){}
						}
						if($y == 1){
							$result = $conn->query("SELECT `fan` FROM `fanbase` WHERE `follower` = '$name'");
							$t = 0;
							foreach($result as $y){
								foreach($y as $y){
									$uze[$t]=$y;
									$t += 1;
								}
							}
						}
						?>

						<div style="max-height:600px; overflow:auto;" class="table-responsive-vertical shadow-z-1">
						  <!-- Table starts here -->
							<table id="table" class="table table-hover table-mc-light-blue">
								<thead>
									<tr>
										<th>#</th>
										<th>Username</th>
										<th>Followers</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = $mysqlpage+1;
									$result = $conn->query("SELECT * FROM `fans` ORDER BY `fans`.`followers` DESC LIMIT $mysqlpage,20");
									foreach($result as $x){
										$s = 0;
										if($t>0){
											foreach($uze as $u){
												if($u == $x['fan']){
													$s = 1;
												}
											}
										}
										?>
										<tr class="tr2">
											<td data-title="ID"><?php echo $i; ?></td>
											<td data-title="Name"><a href="/dash.php?i=2&fan=<?php echo $x['fan']; ?>" target="_blank">@<?php echo $x['fan']; ?></a></td>
											<td data-title="Status"><?php echo $x['followers']; ?></td>
											<?php if($x['fan']!=$name && $s ==0){ ?>
												<td data-title="Status">
													<button onclick="if(confirm('Are you sure?')){follow1('<?php echo $x['fan']; ?>');};" class="btn btn-primary">FOLLOW</button>
												</td>
											<?php }elseif($s == 1){ ?>
												<td data-title="Status">
													<button onclick="if(confirm('Are you sure?')){unfollow1('<?php echo $x['fan']; ?>');};" class="btn btn-danger">UNFOLLOW</button>
												</td>
											<?php }else{ ?>
												<td data-title="Status">

												</td>
											<?php } ?>
										</tr>

										<?php
										$i += 1;
									}
									?>
								</tbody>
							</table>
							<div class="col-md-12" style="text-align:center;">
								<?php if($page>0){ ?> <a class="btn btn-primary" href="/dash.php?i=2">First page</a>
									<a class="btn btn-primary" href="/dash.php?i=2&p=<?php echo $page-1; ?>">Previous page</a> <?php } ?>
								<a class="btn btn-primary" href="/dash.php?i=2&p=<?php echo $page+1; ?>">Next page</a>
							</div>
						</div>

				 <?php } ?>


				</div><!-- /contact -->
			</div><!-- /card -->
		</div><!-- /Col-12 -->
	</div><!-- /Row -->
	<?php } ?>
</div> <!-- /Content -->

<!DOCTYPE html>

<html>

<?php include "header.html";?>


<div id="left_panel">
	<div id="svg2_panel"></div>
	<div id="svg1_panel"></div>
</div>

<div id="right_panel">
	<div class="panel-group">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" class="active" href="#collapsible_alignment_input_box">Input read alignments</a>
				</h4>
			</div>
			<div class="panel-collapse collapse in" id="collapsible_alignment_input_box">
				<div class="panel-body">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#sam">paste sam</a></li>
						<li><a data-toggle="tab" href="#bam">load bam file</a></li>
						<li><a data-toggle="tab" href="#coords">coordinates</a></li>
						<li><a data-toggle="tab" href="#igv">from igv</a></li>
					</ul>
					
					<div class="tab-content">
						<div id="sam" class="tab-pane fade in active">
							<textarea class="form-control" placeholder="Paste lines from a sam file"  id="sam_input"></textarea>
							<span id="sam_info_icon" ><span class="glyphicon glyphicon-info-sign"></span> Show example</span>
						</div>
						<div id="bam" class="tab-pane fade">
							<h5>Select bam and corresponding bam.bai</h5>
							<input type="file" name="files[]" id="bam_file"	multiple />
							<span id="bam_info_icon" ><span class="glyphicon glyphicon-info-sign"></span> Instructions</span>
						</div>
						<div id="coords" class="tab-pane fade">
							<textarea class="form-control" placeholder="Paste lines from a coordinates file (show-coords -lTH)"  id="coords_input"></textarea>
							<span id="coords_info_icon"> <span class="glyphicon glyphicon-info-sign"></span> Show example</span>
						</div>
						<div id="igv" class="tab-pane fade">
							<p> Update to the newest version of IGV. Click on a read of interest within IGV and choose "Send to Ribbon"</p>
							<h4>Data from IGV:</h4>
							<pre readonly id="igv_stats">(empty)</pre>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-group" id="region_selector_panel">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" class="active" href="#collapsible_region_picking_box">Select position</a>
				</h4>
			</div>
			<div class="panel-collapse collapse in" id="collapsible_region_picking_box">
				<div class="panel-body">
					<input class="tiny_input" id="region_chrom" value="chr1"> : 
					<input class="small_input" id="region_start" value="0">
					<!-- - <input class="small_input" id="region_end" value="100000"> -->
					<button id="region_go">Go</button>
				</div>
			</div>
		</div>
	</div>


	<div class="panel-group" id="region_settings_panel">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" class="active" href="#collapsible_region_settings_box">Filter reads</a>
				</h4>
			</div>
			<div class="panel-collapse collapse in" id="collapsible_region_settings_box">
				<div class="panel-body">
					<table id="settings_table">
							<tr>
								<td width="45%">Minimum mapping quality for best alignment: </td>
								<td width="15%"><span id="region_mq_label">0</span></td>
								<td width="35%"><div id="region_mq_slider"></td>
							</tr>
							<tr><td>Number of alignments</td>
								<td> <span id="num_aligns_range_label"></span> </td>
								<td> <div id="num_aligns_range_slider"></div> </td>
							</tr>
					</table>
				</div>
			</div>
		</div>
	</div>

<!-- 
	<div >
		<h4 id="select_reads">Select reads</h4>
		<div id="sam_table_box">
			<table id="sam_table"></table>
		</div>
	</div>
 -->

	<div id="user_message" class="alert alert-default" role="alert"></div>

	<div class="panel-group" id="settings">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" class="active" href="#collapsible_detail_settings_box">Detailed view settings</a>
				</h4>
			</div>
			<div class="panel-collapse collapse in" id="collapsible_detail_settings_box">
				<div class="panel-body">
					<form>
						<table id="settings_table">
							<tr><td class="table_title" colspan="5">General settings</td></tr>
							<tr>
								<td width="45%">Minimum mapping quality: </td>
								<td width="15%"><span id="mq_label">0</span></td>
								<td width="35%"><div id="mq_slider"></td>
							</tr>
							<tr>
								<td>Minimum indel size to split: </td>
								<td><span id="indel_size_label">inf</span></td>
								<td><div id="indel_size_slider"></td>
								
							</tr>
							<tr><td>Show only reference chromosome lengths from header</td>
								<td><input id="only_header_refs_checkbox" type="checkbox"></td>
							</tr>	


							<tr id="table_sep">
								<td colspan="5">
									
									<label class="radio-inline">
										<input class="ribbon_vs_dotplot" id="select_ribbon" type="radio" name="ribbon_vs_dotplot" value="ribbon">Ribbon plot
									</label>

									<label class="radio-inline">
										<input class="ribbon_vs_dotplot" id="select_dotplot"  type="radio" name="ribbon_vs_dotplot" value="dotplot">Dot plot
									</label>

								</td>
							</tr>

							<tr class="dotplot_settings"><td class="table_title" colspan="5">Dot plot settings</td></tr>
							<tr class="dotplot_settings">
								<td>Colors on dotplot: </td><td><input id="colors_checkbox" type="checkbox" checked></td>
							</tr>
							<tr class="ribbon_settings"><td class="table_title" colspan="5">Ribbon plot settings</td></tr>
							<tr class="ribbon_settings">
								<td>Ribbon outline: </td><td><input id="outline_checkbox" type="checkbox" checked></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var json_post = undefined;
	json_post = "<?php 
		if (isset($_GET['var1'])) {
			$data=escapeshellcmd($_GET['var1']);
		} else {
			$data="";
		}
		echo $data;
		?>";
	console.log("json_post:", json_post);
</script>

<!-- Libraries -->
<script src="js/d3.v3.min.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<!-- Special range slider -->
<script src="js/jquery-ui.min.js"></script>

<!-- Library from bam.iobio for reading a bam file -->
<script src="js/bam/class.js"></script>
<script src="js/bam/bin.js"></script>
<script src="js/bam/inflate.js"></script>
<script src="js/bam/bam.js"></script>
<script src="js/bam/bam.iobio.js"></script>

<!-- Main -->
<script src="js/vis.js"></script>


</body>
</html>


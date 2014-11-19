<?php
	$imageSrc = "images/TableGui/circle.gif";
?>

<div id="tableGui">
	<div id="grid">
		<div class="row"> <!--1-->
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">0</span>
				</a>
			</div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">1</span>
				</a>
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
		</div>
		<div class="row"> <!--2-->
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">15</span>
				</a>
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">2</span>
				</a>
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
		</div>
		<div class="row"> <!--3-->
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">14</span>
				</a>
			</div>
			<div class="col square">
				<!--div class="middleContent square">
					<div id="fadeBarrier">
						<p>Station: <span class="debug"></span></p>
						<label>Drink Name:</label>
						<input type="text" name="drinkName" />
						<label>Volume</label>
						<input type="text" name="volume" />
						<label>Proof</label>
						<input type="text" name="proof" />
					</div>
				</div-->
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">3</span>
				</a>
			</div>
			<div class="col square"></div>
		</div>
		<div class="row"> <!--4-->
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
		</div>
		<div class="row"> <!--5-->
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">13</span>
				</a>
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">4</span>
				</a>
			</div>
		</div>
		<div class="row"> <!--6-->
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">12</span>
				</a>
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">5</span>
				</a>
			</div>
		</div>
		<div class="row"> <!--7-->
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
		</div>
		<div class="row"> <!--8-->
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">11</span>
				</a>
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">6</span>
				</a>
			</div>
			<div class="col square"></div>
		</div>
		<div class="row"> <!--9-->
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">10</span>
				</a>
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">7</span>
				</a>
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
		</div>
		<div class="row"> <!--10-->
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">9</span>
				</a>
			</div>
			<div class="col square">
				<a onclick="javascript:StationClick(this)">
					<img class="stationImage" src="<? echo $imageSrc; ?>">
					<span class="stationLabel">8</span>
				</a>
			</div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
			<div class="col square"></div>
		</div>
		
	</div>

	<div id="tableSide">
		<div id="fadeBarrier">
			<p>Station: <span class="stationValue"></span></p>
			<label>Drink Name:</label>
			<input type="text" name="drinkName" />
			<label>Volume</label>
			<input type="text" name="volume" />
			<label>Proof</label>
			<input type="text" name="proof" />
		</div>
	</div>
</div>
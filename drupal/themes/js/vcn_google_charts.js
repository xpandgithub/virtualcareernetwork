/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


function drawWageChart(chartId, usHigh, usMedian, usLow, stateHigh, stateMedian, stateLow, state, zipHigh, zipMedian, zipLow, zipCode) {
  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Levels');
  data.addColumn('number', 'United States');

  // format the second and third column as a percent
  var formatter = new google.visualization.NumberFormat({pattern: '$#,###'});
  
  // define the data in the chart

  if (zipCode) {
	  data.addColumn('number', state);
	  data.addColumn('number', zipCode);
	  data.addRows([
	                ['High', parseInt(usHigh), parseInt(stateHigh), parseInt(zipHigh)],
	                ['Median', parseInt(usMedian), parseInt(stateMedian), parseInt(zipMedian)],
	                ['Low', parseInt(usLow), parseInt(stateLow), parseInt(zipLow)]
	              ]);
	  formatter.format(data, 1);
	  formatter.format(data, 2);
	  formatter.format(data, 3);
  } else {
	  data.addRows([
	                ['High', parseInt(usHigh)],
	                ['Median', parseInt(usMedian)],
	                ['Low', parseInt(usLow)]
	              ]);
	  formatter.format(data, 1);
  }
  
  // Set chart options
  var options = {
                  width:300,
                  height:200,
                  chartArea:{left:50, top:30},
                  focusTarget:'category',
                  series:{0:{color:'#4D89F9'}, 1:{color:'#C6D9FD'}, 2:{color:'#330066'}},
                  hAxis:{minValue:0, format:'$#,###'}
                };

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.BarChart(document.getElementById(chartId));
  chart.draw(data, options);
}

function drawCommonEducationLevelsChart(chartId, educationLevelsArr) {
  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Levels');
  data.addColumn('number', 'Percentage');

  // format the second column as a percent
  var formatter = new google.visualization.NumberFormat({pattern: '#%'});
  
  // need to substitue "<" for "Less than" so it fits on the graph
  educationLevelsArr[0] = educationLevelsArr[0];
  educationLevelsArr[2] = educationLevelsArr[2];
  educationLevelsArr[4] = educationLevelsArr[4];
  educationLevelsArr[6] = educationLevelsArr[6];
  educationLevelsArr[8] = educationLevelsArr[8];
  educationLevelsArr[10] = educationLevelsArr[10];
  educationLevelsArr[12] = educationLevelsArr[12];
  
  // define the data in the chart
  if (educationLevelsArr) {
    data.addRows([
                   [educationLevelsArr[0], (Math.round(parseFloat(educationLevelsArr[1])) / 100)],
                   [educationLevelsArr[2], (Math.round(parseFloat(educationLevelsArr[3])) / 100)],
                   [educationLevelsArr[4], (Math.round(parseFloat(educationLevelsArr[5])) / 100)],
                   [educationLevelsArr[6], (Math.round(parseFloat(educationLevelsArr[7])) / 100)],
                   [educationLevelsArr[8], (Math.round(parseFloat(educationLevelsArr[9])) / 100)],
                   [educationLevelsArr[10], (Math.round(parseFloat(educationLevelsArr[11])) / 100)],
                   [educationLevelsArr[12], (Math.round(parseFloat(educationLevelsArr[13])) / 100)]
                 ]);
    formatter.format(data, 1);
  }
  
  // Set chart options
  var options = {
		  width:490,
                  height:300,
                  chartArea:{left:150, top:15, width:300, height:250},
                  focusTarget:'category',
                  legend:{position:'none'},
                  series:{0:{color:'#4D89F9'}},
                  hAxis:{minValue:0, format:'#%'}
                };

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.BarChart(document.getElementById(chartId));
  chart.draw(data, options);
}

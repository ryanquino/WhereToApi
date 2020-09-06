window.onload = function () {
	var chart = new CanvasJS.Chart("chartContainer",
	{
		theme: "theme2",
		title:{
			text: ""
		},		
		data: [
		{       
			type: "pie",
			toolTipContent: "<b>{label}</b>: {y}%",
            indexLabelFontSize: 12,
		    indexLabel: "{label} - {y}%",
			dataPoints: [
				{  y: 25.27, label: "Public Sale" },
				{  y: 10.11, label: "Company" },
				{  y: 5.5, label: "Beta" },
                {  y: 20.21, label: "Locked SDGLIV"},
                {  y: 5.5, label: "Bounty" },
                {  y: 5.5, label: "Presale" },
                {  y: 5.5, label: "Reserved Team" },
                {  y: 5.5, label: "Livelihood" },
                {  y: 5.5, label: "Bonus Payroll" },
                {  y: 5.5, label: "Calamity, Gov't Support" },
                {  y: 5.5, label: "Charity and Agriculture" },


			]
		}
		]
	});
	chart.render();
}


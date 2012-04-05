// JavaScript Document

var openChat = new Array();
var minChat = new Array();
var ids = new Array();
openChat = [0,0,0,0,0];
minChat = [0,0,0,0,0];
ids = [0,0,0,0,0];

function displayChat(to_id) {
	
	var line = "";
	var assignedNumber = 0;
	
	for (i=0; i<5; i++)
		if (ids[i]==to_id) exit;
	
	if (openChat[1] == 0)
		assignedNumber = 1;
		else if (openChat[2] == 0)
		assignedNumber = 2;
		else if (openChat[3] == 0)
		assignedNumber = 3;
		else if (openChat[4] == 0)
		assignedNumber = 4;
	
	ids[assignedNumber] = to_id;

	line = "<iframe src=\"../pchat.php?to="+to_id+"&window="+assignedNumber+"\" width=\"250px\" height=\"250px\" scrolling=\"none\" frameborder=\"0\">";
	line += "<p>Your browser does not support private chats.</p>";
	line += "</iframe>";
	
	if (openChat[1] == 0) {
		openChat[1] = 1;
		document.getElementById('popup1').innerHTML = line;
		document.getElementById('popup1').style.display = 'inline';
	} else if (openChat[2] == 0) {
		openChat[2] = 1;
		document.getElementById('popup2').innerHTML = line;
		document.getElementById('popup2').style.display = 'inline';
	} else if (openChat[3] == 0) {
		openChat[3] = 1;
		document.getElementById('popup3').innerHTML = line;
		document.getElementById('popup3').style.display = 'inline';
	} else if (openChat[4] == 0) {
		openChat[4] = 1;
		document.getElementById('popup4').innerHTML = line;
		document.getElementById('popup4').style.display = 'inline';
	};
	
	$.post("utils/change_pchat_status.php", {win: assignedNumber, stat: 1, id: to_id});

}


function closeChat(number) {	
	if (number == 1) {
		openChat[1] = 0;
		document.getElementById('popup1').innerHTML = "";
		document.getElementById('popup1').style.height = '250px';
		document.getElementById('popup1').style.display = 'none';
	}
	if (number == 2) {
		openChat[2] = 0;
		document.getElementById('popup2').innerHTML = "";
		document.getElementById('popup2').style.height = '250px';
		document.getElementById('popup2').style.display = 'none';
	}
	if (number == 3) {
		openChat[3] = 0;
		document.getElementById('popup3').innerHTML = "";
		document.getElementById('popup3').style.height = '250px';
		document.getElementById('popup3').style.display = 'none';
	}
	if (number == 4) {
		openChat[4] = 0;
		document.getElementById('popup4').innerHTML = "";
		document.getElementById('popup4').style.height = '250px';
		document.getElementById('popup4').style.display = 'none';
	}
	$.post("utils/change_pchat_status.php", {win: number, stat: 0});
	ids[number] = '0';
	
}


function minimizeChat(number) {	
	if (number == 1)
		if (minChat[1] == 0) {
			minChat[1] = 1;
			document.getElementById('popup1').style.height = "20px";
		} else {
			minChat[1] = 0;
			document.getElementById('popup1').style.height = "250px";
		}
	if (number == 2)
		if (minChat[2] == 0) {
			minChat[2] = 1;
			document.getElementById('popup2').style.height = "20px";
		} else {
			minChat[2] = 0;
			document.getElementById('popup2').style.height = "250px";
		}
	if (number == 3)
		if (minChat[3] == 0) {
			minChat[3] = 1;
			document.getElementById('popup3').style.height = "20px";
		} else {
			minChat[3] = 0;
			document.getElementById('popup3').style.height = "250px";
		}
	if (number == 4)
		if (minChat[4] == 0) {
			minChat[4] = 1;
			document.getElementById('popup4').style.height = "20px";
		} else {
			minChat[4] = 0;
			document.getElementById('popup4').style.height = "250px";
		}

}

function pchat_confirm(uid, sid) {
	$.post("utils/change_pchat_status.php", {confirm: 999, id: sid});
	displayChat(uid);	
}

function pchat_cancel(sid) {
	$.post("utils/change_pchat_status.php", {cancel: 999, id: sid});
}
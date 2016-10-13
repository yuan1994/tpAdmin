
//the window.onload wrapper around these object constructors is just for demo purposes
//in practise you would put them in an existing load function, or use a scaleable solution:
//http://www.brothercake.com/site/resources/scripts/domready/
//http://www.brothercake.com/site/resources/scripts/onload/
window.onload = function()
{



	//initialise the docking boxes manager
	var manager = new dbxManager('main'); 	//session ID [/-_a-zA-Z0-9/]


	//create new docking boxes group
	var sidebar = new dbxGroup(
		'sidebar', 		// container ID [/-_a-zA-Z0-9/]
		'vertical', 		// orientation ['vertical'|'horizontal']
		'7', 			// drag threshold ['n' pixels]
		'no',			// restrict drag movement to container axis ['yes'|'no']
		'10', 			// animate re-ordering [frames per transition, or '0' for no effect]
		'yes', 			// include open/close toggle buttons ['yes'|'no']
		'open', 		// default state ['open'|'closed']

		'open', 		// word for "open", as in "open this box"
		'close', 		// word for "close", as in "close this box"
		'click-down and drag to move this box', // sentence for "move this box" by mouse
		'click to %toggle% this box', // pattern-match sentence for "(open|close) this box" by mouse
		'use the arrow keys to move this box', // sentence for "move this box" by keyboard
		', or press the enter key to %toggle% it',  // pattern-match sentence-fragment for "(open|close) this box" by keyboard
		'%mytitle%  [%dbxtitle%]' // pattern-match syntax for title-attribute conflicts
		);



};

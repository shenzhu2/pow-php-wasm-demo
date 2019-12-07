// global vars
var nIntervId;

function get_pow_solution( hostname, timestamp, rand, difficulty ){

	document.getElementById( "output-span" ).innerHTML = "Solving Proof of Work"

	// we need use setInterval() for let browser update DOM in innerHTML call.
	// for this work we have C update JS var so counter has same value on next
	// call. we use pointer.
	var counterPtr = Module._malloc(8);

	get_pow_solution = Module.cwrap( 'get_pow_solution', 'number', ['string', 'string', 'string', 'number', 'number'] );
	nIntervId = setInterval( get_pow_solution, 1, hostname, timestamp, rand, difficulty, counterPtr );

}

function submit_pow_solution( hostname, timestamp, rand, difficulty, counterPtr ){

	// get value of counter then free memory
	var counter = Module.getValue( counterPtr, 'i8*' );
	Module._free( counterPtr );

	var hash = Sha256.hash( Sha256.hash( hostname + timestamp + rand + counter ) );

	var output = "Send proof of work solution\n"
	output += "<blockquote>\n"
	output += "hostname = " +hostname+ "<br/>\n"
	output += "timestamp = " +timestamp+ "<br/>\n"
	output += "rand = " +rand+ "<br/>\n"
	output += "counter = " +counter+ "<br/>\n"
	output += "difficulty = " +difficulty+ "<br/>\n"
	output += "hash = " +hash+ "<br/>\n"
	output += "</blockquote>\n"
	document.getElementById( "output-span" ).innerHTML = output;

	// send solution to server web
	httpRequest = new XMLHttpRequest()
	httpRequest.open('POST', '/submit-pow-solution.php')
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.send(
	 'hostname=' +encodeURIComponent(hostname)+ '&' +
	 'timestamp=' +encodeURIComponent(timestamp)+ '&' +
	 'rand=' +encodeURIComponent(rand)+ '&' +
	 'counter=' +encodeURIComponent(counter)+ '&' +
	 'difficulty=' +encodeURIComponent(difficulty)
	);

	httpRequest.onreadystatechange = function(){
		// Process the server response here.
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			if (httpRequest.status === 200) {
				pow_solution_accepted()
			} else {
				pow_solution_rejected()
			}
		}
	}
}

function pow_solution_accepted(){

	var output = document.getElementById( "output-span" ).innerHTML
	output += "<p>Server accepted solution!</p>";
	output += '<p>You can now access <a href="/pow-walled-content.php">/pow-walled-content.php</a></p>';
	document.getElementById( "output-span" ).innerHTML = output;

}

function pow_solution_rejected(){

	var output = document.getElementById( "output-span" ).innerHTML
	output += "<p>ERROR Server rejected solution</p>"
	document.getElementById( "output-span" ).innerHTML = output

}

function update_hashrate( hashrate ){
	document.getElementById( "output-span" ).innerHTML = hashrate + " hash/sec";
}

function wasmEnabled() {

	const supported = (() => {
		try {
			if (typeof WebAssembly === "object" && typeof WebAssembly.instantiate === "function") {
				const module = new WebAssembly.Module(Uint8Array.of(0x0, 0x61, 0x73, 0x6d, 0x01, 0x00, 0x00, 0x00));
				if (module instanceof WebAssembly.Module)
					return new WebAssembly.Instance(module) instanceof WebAssembly.Instance;
			}
		} catch (e) {
		}
		return false;
	})();

	return supported;

}

#include <stdio.h>
#include <string.h>
#include "sha-256.h"
#include <emscripten.h>
#include <time.h>

// send hashrate to javascript for update UI
EM_JS(void, update_hashrate_stub, (long hashrate), {
	update_hashrate( hashrate );
});

// tell javascript stop setInterval() execute C
EM_JS(void, clearInterval_stub, (), {
	clearInterval(nIntervId);
});

// send solution to javascript for POST to web server
EM_JS(void, submit_pow_solution_stub, (char *hostname_string, char *timestamp, char *rand, unsigned int difficulty, unsigned int *counterPtr), {
	submit_pow_solution(
		UTF8ToString(hostname_string),
		UTF8ToString(timestamp),
		UTF8ToString(rand),
		difficulty,
		counterPtr
	);
});

// sha256 helper
static void hash_to_string( char string[65], const uint8_t hash[32] ){
	size_t i;
	for (i = 0; i < 32; i++) {
		string += sprintf(string, "%02x", hash[i]);
	}
}

// check if hash is solution
static int hash_is_solution( char *hash, int difficulty ){

	for( int i=0; i<difficulty; i++ ){
		char c = hash[i];
		if( c != '0' ){
			return 0;
		}
	}

	return 1;

}

// main function for solve pow. it start from *counterPtr which is malloc() in
// javascript and pass as pointer to C so this C function can be called using
// the browser setInterval() function (necessary for update UI with hashrate)
int get_pow_solution( char *hostname_string, char *timestamp, char *rand, unsigned int difficulty, unsigned int *counterPtr ) {

	unsigned int lastCounter = (*counterPtr);
	long lastTime = time(NULL);

	int solved = 0;
	while( solved == 0 ){

		uint8_t output[32];
		char output_string[65];

		char counter_string[10];
		sprintf( counter_string, "%d", (*counterPtr) );

		long timeDiff;
		long hashrate;

		char input[184] = "";
		strncat( input, hostname_string, 128 );
		strncat( input, timestamp, 25 );
		strncat( input, rand, 20 );
		strncat( input, counter_string, 10 );

		// hash one
		calc_sha_256( output, input, strlen(input) );
		hash_to_string( output_string, output );

		// hash two (like bitcoin)
		calc_sha_256( output, output_string, strlen(output_string) );
		hash_to_string( output_string, output );

		solved = hash_is_solution( output_string, difficulty );
		if( solved == 1 ){
			// tell javascript stop execute this C function
			clearInterval_stub();

			// solution found. call javascript to send solution with ajax.
			submit_pow_solution_stub( hostname_string, timestamp, rand, difficulty, counterPtr );

			return (*counterPtr);
		}

		// update hashrate so user knows pow not frozen
		if( (*counterPtr) % 100 == 0 ){

			timeDiff = time(NULL) - lastTime;

			// update at min every 4 seconds
			if( timeDiff > 4 ){
				hashrate = ((*counterPtr)-lastCounter)/timeDiff;

				// tell javascript update hashrate in UI
				update_hashrate_stub( hashrate );

				lastTime = time(NULL);
				lastCounter = (*counterPtr);

				return (*counterPtr);
			}
		}

		// increment nonce for next check of hash() solution
		(*counterPtr)++;
	}

	return (*counterPtr);
}


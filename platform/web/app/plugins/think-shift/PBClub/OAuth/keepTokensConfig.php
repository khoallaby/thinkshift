<?php
/**
 * keepTokensConfig contains pre-sets for keepTokens.php
 * August 17th, 2016
 * Author : John Borelli
 * Company : The Plan B Club, LLC
 * Client : AZ Naturals
 */

/*
 * Constant declarations (change for your environment)
 *      > CLIENT_ID and CLIENT_SECRET are the values from your IS Mashery account
 *      > REDIRECT_URL is JUST the domain/path (not the filename)
 *      > REDIRECT_FILENAME is the name of the file to post back to (should not need to change)
 *      > SAVE_FILENAME filename to save resulting access/refresh token information to
 *      > TIME_ZONE this is to permit proper notation in the log file
 */

//const CLIENT_ID="6yhjpydtvcpa67ep45quf2j7";
//const CLIENT_SECRET="xZfF3NxJEP";
const CLIENT_ID='zsa5jgt4x24c4txprpzhrjt2';
const CLIENT_SECRET='gPtJerxDpt';
const REDIRECT_URL="https://108.167.137.94/oauth/";  // this MUST be a https address (w/o filename)
const REDIRECT_FILENAME="tokenStart.php";  // this is JUST the name of the postback file
const SAVE_FILENAME="keepTokens.tok";
const TIME_ZONE='America/Los_Angeles';
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

const CLIENT_ID='9sbtkn2vfjrr7cp93yaswgpq';
const CLIENT_SECRET='St9WnkKkk8';
const REDIRECT_URL="";  // this MUST be a https address (w/o filename)
const REDIRECT_FILENAME="";  // this is JUST the name of the postback file
const SAVE_FILENAME="keepTokens.tok";
const TIME_ZONE='America/Los_Angeles';

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Socialite;
use Google_Client;
use Google_Service_PhotosLibrary;
use Google_Service_PhotosLibrary_SearchMediaItemsRequest;

class GalleryController extends Controller
{
    public function show($albumId = null, $nextPage = null) {
    	$user = Auth::User();
		$google_client_token = [
	        'access_token' => $user->provider_token,
	        'refresh_token' => $user->refresh_token,
	        'expires_in' => $user->expires_in
	    ];
	    /*
	    {
		    "web": {
		        "client_id": "920511532692-ka8sqab1tet930ubfom5j3vua1i1q2vr.apps.googleusercontent.com",
		        "project_id": "mercurial-cairn-208915",
		        "auth_uri": "https://accounts.google.com/o/oauth2/auth",
		        "token_uri": "https://accounts.google.com/o/oauth2/token",
		        "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
		        "client_secret": "gPGKIW3wVghzdIAzt2Uvo35C",
		        "redirect_uris": [
		            "http://socialgal.com/login/google/callback"
		        ],
		        "javascript_origins": [
		            "http://socialgl.com"
		        ]
		    }
		}s
		*/
	    $client = new Google_Client();
	    $client->setApplicationName(env("APP_NAME","Laravel"));
	    $client->setDeveloperKey(env('GOOGLE_KEY'));
	    $client->setClientId(env('GOOGLE_KEY'));
	    $client->setClientSecret(env('GOOGLE_SECRET'));
	    $client->setAccessToken(json_encode($google_client_token));

	    $photos = new Google_Service_PhotosLibrary($client);
	    if(!empty($albumId)) {
	    	$searchMediaItems = new Google_Service_PhotosLibrary_SearchMediaItemsRequest();
	    	$searchMediaItems->setAlbumId($albumId);
	    	$searchMediaItems->setPageSize(10);
	    	if(!empty($nextPage)) {
	    		$searchMediaItems->setPageToken($nextPage);
	    	}
	    	$nextPage = $photos->mediaItems->search($searchMediaItems)->nextPageToken;
	    	$photoArr = array();
	    	foreach ($photos->mediaItems->search($searchMediaItems)->mediaItems as $value) {
	    		// dd($value['baseUrl']);
	    		$photoArr[] = $value['baseUrl'];
	    	}
	    	// dd($photoArr);
	    	// dd($photos->mediaItems->search($searchMediaItems));
	    	return view('listPhotos', compact('photoArr', 'nextPage', 'albumId'));
	    }
		// dd($photos->sharedAlbums->listSharedAlbums()->getSharedAlbums()	);
		// $albumID = $photos->sharedAlbums->listSharedAlbums()->getSharedAlbums()['id'];
		// dd($albumID);
		$albInfo = array();
		foreach($photos->sharedAlbums->	listSharedAlbums()->getSharedAlbums() as $alb){
			// dd($alb);
			$albInfo[$alb->title] = $alb->id;
		}

		// dd($albInfo);
		// dd($photos->sharedAlbums->	listSharedAlbums()->getSharedAlbums()	);
	    return view('listAlbum', compact('albInfo'));

    }
}

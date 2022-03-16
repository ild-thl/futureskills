# Changelog

## [Unreleased v0.7]

## 2022-03-15/ml
# added
- MiniDataSet (id, title, image, visible) for the management sites, that is controlled by middleware

## 2202-03-14/kw
## Added
-funktion: saving textsearches for filtering offers 

## 2022-03-01/kw
## Changed
-logout function changed to only delete token passed in request and its corresponding refreshtoken

## [Released v0.6]

## 2022-02-01/kw
## Added
-new route GET::{{url}}/api/logout      added
-route deletes accesstokens and refreshtokens of user

## 2022-01-10/kw
## Changed
- Textsearch response now sorted by score

### 2021-12-15/ml
## Added
- Passport config
- token expire variables in AuthServiceProvider

## [Released v0.5]

## 2021-12-06/kw
## Added
- Textsearch function that works with filters
- new migration for adding fulltext search indexes to columns "title,author,description" of "offers" table
## Changed
- renamed buildFilterQuery to buildFilterTextsearchQuery to reflect new functionality

## 2021-12-02/dk
## Changed
- Pagination results are sorted by sort_flag

## 2021-11-17/dk
## Changed
- fixed filters ignoring competences when the first offer had none assigned

## 2021-11-10/kw
## Changed
- changed relatedOffers synchronization in "saveRelatedData" function in OfferController back to previous functionality 
- changed relatedOfferRule back to previous state

## 2021-11-08/kw
## Added
- "location" to Offer(update/store)request rules 
- "location" to ExternalOfferUpdaterequest rules 
## Changed
- saveRelatedData function to delete rows in pivot tables related to Offer
- Offer related pivot tables no longer delete data unless explicitly sending delete request with §key =(no value) ;  
- relatedOfferRule 

## [Released v0.4.2]

## 2021-10-14/kw
## Added
- added filter function to paginated responses (paginatedOffers, paginatedReducedOffers)
- added Request Class FilterRequest
## Changed
- changed request method of 'list/offer/short/paginated/{offerCount}' from GET to POST 
- changed request method of 'offer/paginated/{offerCount}' from GET to POST 

## 2021-10-04/dk
## Added
- New Route search/offer/latest for recently added offers

## [Released] v0.4.1

## 2021-10-04/dk
## Changed
- Eloquent related Offers got their own model to prevent loops/memory leaks

## [Released] v0.4

## 2021-09-20/dk
## Changed
- Route offer/{id} outputs some additional information about related offers

## 2021-09-16/kw
## Added
- added paginated_offers function in Offercontroller (pagination kursliste)
- added paginated_reduced_offers function in Offercontroller
- added testroute {api}/offer/paginated/{offerCount}
- added testroute{api}/list/offer/short/paginated/{offerCount}

## 2021-09-14/ml
### added
- added function to get Offers by Keyword

## 2021-09-03/dk
### Changed
- Upgraded Laravel to 8
- Upgraded Passport to 10.1
- JWT-Handling changed to support newer required lcobucci/jwt package
### Removed 
- Bot-related functions

## 2021-09-01/ml
### Changed
- offerlist contains keywords (huboffer)

## [Released v0.3.1]

## 2021-08-10/kw
### Added
-userrole in Access Token (temporary solution)

## 2021-08-03/kw
### Added
- Userroles and Permissions added
- Roles : Admin , Institution, Subscriber default
- Permission : store_update_apikey, store_update_institution, store_update_subscription, store_update_offer, store_update_user

## 2021-07-02/kw
### Added
- new routes added to manage apikeys (activate/deactivate apikeys)

## 2021-07-02/kw
### Added
- New Pivot Table api_key_institution added . ManyToMany between api_key and institution
- Middleware AuthorizeApiKey.php to authorize request to add/update external offers added
- Models Institution & Apikey changed to have manyToMany relationship via pivot table
- route added for generating Apikeys
- ApiKeyController added to handle request for new apikey

## [Released] v0.3

## 2021-06-21/dk
### Added
- New Listener to remove old access_tokens

## 2021-06-21/kw
### Added
- AccessToken.php in app/http/laravel/passport/bridge overwrites default AccessToken.php
- AccessTokenRepository.php in app/http/laravel/passport/bridge overwrites default AccessTokenRepository.php
- AccessTokenTrait.php in app/http/league/Entities/Traits overwrites default AccessTokenTrait.php
- username & userid added to AccessTokenTrait
### Removed
- removed new BearerTokenResponse.php, default BearerTokenResponse is now being used again

## 2021-06-10/kw
### Added
- added package ejarnutowski/laravel-api-key in composer.json (https://github.com/ejarnutowski/laravel-api-key)
- AuthorizeApiKey.php in app/http/middleware overwrites laravel-api-key's default  AuthorizeApiKey.php  
- ApiKeyServiceProvider in app/http/providers overwrites laravel-api-key's default PassportServiceProviders
- moved route offer/ext/{institution}/{offer} out of middlewaregroup "auth:api"


## 2021-05-26/dk
### Added
- added new route with short offer list output at /api/offer/short

## 2021-05-25/kw
### Added
- BearerTokenResponse.php in app/http/responsetypes overwrites default BearerTokenResponse.php
- new values (user_name and user_id) to bearerTokenResponse 
- PassportServiceProvider in app/http/providers overwrites default PassportServiceProviders

## [Released] v0.2

## 2021-05-05/dk
### Added
- new UpdateRequest for adding/editing offers from external 
- new Get route for external offers
### Changed
- Changed OfferSeeder image link

## 2021-04-07/dk
### Added
- offer relations can be saved via offer editing
### Changed
- Author saving fixed

## 2021-04-03/dk
### Changed
- Offers cascade on delete
- fixed competence saving process
- moved external update route

## 2021-03-31/dk
### Changed
- Added identifiers and descriptions via migration
### Added
- new route for filter tags: /filter/tags

## 2021-03-15/dk
### Added
- Offer_relations table
- Migrations for DB redesign
### Changed
- OfferController and -Requests regarding redesign
## 2021-02-15/ml
### Added
- CookieController, Cookie for the Bot-UserID
- New Package in Composer (php-jwt)

### Changed
- BotConfigController, generates Token fpr Secure Bot
- New Routes

## 2021-02-02/ml
### Added
- BotConfigController.php, reads static JSON File for Bot-Konfiguration (Urls in config/bot.php)

## 2021-01-21/dk
### Added
- New migration for adding external id and status to Offer
- New route for editing offers by external ids
- Special rules for external id OfferUpdateRequest

## [Released] v0.1.2

## 2020-12-03/dk
### Changed
- Fixed offer sorting on offer->index
- added and fixed some riles for offer storing and updating

## [Released] v0.1.1

## 2020-12-03/dk
### Added
- doctrine/dbal for DB migration changes of columns
### Changed
- Offer model has more fields and returns more data after storage
- Subscription table is deleted and created without constraints
- Set lcobucci/jwt to 3.3.2 for compatibility
- offer index is returned sorted by sort_flag

## [Released] v0.1

## 2020-11-10/dk
### Added
- New routes for offers and institutions without authentication


# SeAT-Info
SeAT-Info is a SeAT module that adds a small article systems for example as a corporation bulletin, or for explanations
on how to use seat.

![screenshot of the seat-info plugin](screenshot.png)

## Usage
### Roles
#### View Role
Allows you to view articles and adds the `Start` and `Articles` page to the sidemenu.
#### Edit Role
This role allows you to create, manage and delete articles aswell as to upload images and other resources usable in the 
articles.

### Editor
The editor supports a markup language that's kinda close to HTML, but not quite. Currently, the parser is relatively 
strict, and for example you can't have spaces in the tags where there doesn't need to be one. E.g. `<a></a>` is valid, 
but `< a ></ a>` isn't.

For all available tags, please see the [documentation](documentation.md)

Currrently there are a lot of features missing that might be useful, and it could use some QOL updates. If you have 
specific needs, open an issue or pull request.

## Installation
I can also recommend reading through the [official seat documentation](https://eveseat.github.io/docs/community_packages/).

### Docker Install

Open your .env file and edit the SEAT_PLUGINS variable to include the package.

```
# SeAT Plugins
SEAT_PLUGINS=recursivetree/seat-info
```

Now run
```
docker-compose up
```
and the plugin should be installed

### Barebone Install

In your seat directory:

```
sudo -H -u www-data bash -c 'php artisan down'
sudo -H -u www-data bash -c 'composer require recursivetree/seat-info'
sudo -H -u www-data bash -c 'php artisan vendor:publish --force --all'
sudo -H -u www-data bash -c 'php artisan migrate'
sudo -H -u www-data bash -c 'php artisan seat:cache:clear'
sudo -H -u www-data bash -c 'php artisan config:cache'
sudo -H -u www-data bash -c 'php artisan route:cache'
sudo -H -u www-data bash -c 'php artisan up'
```

## Increase the upload file size
Per default, the configuration for the max allowed file size of php is rather low, meaning you can't upload big files in
the resources tab. if you use a barebone install, you can fix it like this:

1. Open the `/etc/php/7.3/fpm/php.ini ` file, for example with nano:
    ```
    nano /etc/php/7.3/fpm/php.ini 
    ```
2. Change this line
    ```
    upload_max_filesize = 2M
    ```
    to 
    ```
    upload_max_filesize = [the max size you want in megabytes]M
    ```
3. Do the same for `post_max_size` The value should be slightly larger than the value of`upload_max_filesize`.
4. Save and exit
5. Reload the config with:
    ```
    service php7.3-fpm reload
    service nginx reload
    ```
6. Reload the management page and it should state a higher value as the limit.

I haven't looked into how to do this in docker, but it should be similar.

## Donations
Donations are always welcome, although not required. If you end up using this module a lot, I'd appreciate a donation. 
You can give ISK or contract PLEX and Ships to `recursivetree`.


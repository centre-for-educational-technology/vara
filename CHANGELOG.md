# Changelog

## 1.0.0 - 20.01.2022

- Updated Drupal Core to version 9.3.3.
- Local authentication is allowed for administrators. See issue
[74](https://github.com/centre-for-educational-technology/tlu-h5p/issues/74) for more details.
- Logout URL is set to an empty value. See issue
[73](https://github.com/centre-for-educational-technology/tlu-h5p/issues/73) for more details.
- Moved **My materials** into the main menu. See issue
[71](https://github.com/centre-for-educational-technology/tlu-h5p/issues/71) for more details.
- Moved language switched to the top right. Changed visuals to show all the languages in one row. Changed the visuals
to better follow the general logic of the UI. See issue
[70](https://github.com/centre-for-educational-technology/tlu-h5p/issues/71) for more details.
- Exported configurations.

## 1.1.0 - 07.02.2022

- Updated Drupal Core to version 9.3.5.
- Initial basic implementation of Bootstrap-based theme. See issue
[83](https://github.com/centre-for-educational-technology/tlu-h5p/issues/83) for more details.
- Migrated to H5P module version 2.0.0-alpha2. See issue
[93](https://github.com/centre-for-educational-technology/tlu-h5p/issues/93) for more details.
- Made material views tables sortable. See issue
[90](https://github.com/centre-for-educational-technology/tlu-h5p/issues/90) for more details.
- Added allowed plugin configuration to `composer.json` file. See issue
[72](https://github.com/centre-for-educational-technology/tlu-h5p/issues/72) for more details.
- Added composer patches dependency and created a patch to use user display name instead of username for H5P material
author. See issue [78](https://github.com/centre-for-educational-technology/tlu-h5p/issues/78) for more details.
- Removed Editor role and all the associated code. See issue
[91](https://github.com/centre-for-educational-technology/tlu-h5p/issues/91) for more details.
- User language preference will only be set on initial login. See issue
[86](https://github.com/centre-for-educational-technology/tlu-h5p/issues/86) for more details.
- Changed web service e-mail address setting. See issue
[88](https://github.com/centre-for-educational-technology/tlu-h5p/issues/88) for more details.
- Made changes to the tag cloud displayed on the material search page. See issue
[79](https://github.com/centre-for-educational-technology/tlu-h5p/issues/79) for more details.
- Allowed any authenticated user to change the **published** status. See issue
[85](https://github.com/centre-for-educational-technology/tlu-h5p/issues/85) for more details.
- Partial implementation of legal links, mostly a block that points to the addresses that should be used. See issue
[80](https://github.com/centre-for-educational-technology/tlu-h5p/issues/80) for more details.
- Added a few more translations.
- Updated a few modules and dependencies.
- Also includes a few more tunes and fixes here and there.

## 1.1.1 - 17.02.2022

- Updated Drupal Core to version 9.3.6.
- Added several convenience shell scripts for constantly repeated activities. Updated instructions in README file to use
those scripts.
- Added Cookie Policy modal and logic. See issue
[80](https://github.com/centre-for-educational-technology/tlu-h5p/issues/80) for mode details.
- Changed positions of material form fields.
- Changed visuals for tags taxonomy term view and updated translations. Please see
[this](https://github.com/centre-for-educational-technology/tlu-h5p/commit/9eaed1e254198f65c54e1ddddcd569db1e45111e)
commit for more details.
- Made sure that active visuals for main menu work even when query string or hash are present. See issue
[104](https://github.com/centre-for-educational-technology/tlu-h5p/issues/104) for more details.
- Added and activated [Admin toolbar](https://www.drupal.org/project/admin_toolbar) module.
- Added permission to view unlisted materials. Not yet used anywhere. See issue
[89](https://github.com/centre-for-educational-technology/tlu-h5p/issues/89) for more details.
- H5P package upload input will now show maximum file upload size. See issue
[94](https://github.com/centre-for-educational-technology/tlu-h5p/issues/94) for more details.

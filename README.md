# CiviModels

## Summary
CiviModels is an extension that provides a common framework for presenting multiple data models regarding CiviCRM contacts. Specifically the extension:

- creates a tab on a Contact record called "Models"
- provides a permission 'access civimodels' for viewing/loading the "Models" tab
- provides a hook function for model extensions to pass in their data for display
- respects individual model permissions

## Getting Started
- install the extension via your preferred method
- enable the extension
- install and enable any compatible model extensions
- configure permissions as required to manage access to the data

## Details
With larger CiviCRM databases, it may be useful to segment your contacts by way of various attributes and/or behaviours. Common examples include:

- Recency, Frequency, Monetary models for donors - "how much does this person give us on average, and how recently?"
- Email engagement models for supporters - "how often does this person click links inside our mailings?"
- Membership models - "what value do we put on longer held memberships, or different tier memberships?"

Many of these questions can be answered through CiviReports, Contact searches and similar approaches. Models can automate the calculation
of these various "scores" and make them more immediately usable for the creation of Smart Groups, better segmentation of mailing audiences, etc.

The CiviModels extension is designed to provide a single presentation framework for any number of models that you may wish to build and install.
It provides a single place in which all active models' data can be viewed while preserving the ability to manage user access to individual models.

## How it works
The extension creates a new tab for CiviCRM contact records (using the [hook_civicrm_tabset function](https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tabset/)).

The extension also defines a new permission - 'access civimodels' - that manages the visibility of the tab.

Any model whose data you want to display in the tab, makes use of a custom hook function - civimodels_displayCiviModelData - that is invoked whenever the tab is loaded.

When the tab is loaded, the hook function fires, collects all available models' data and renders
it inside the tab in a two-column layout.

## Using the extension

When building your own model extension, there are really only two particular requirements:
- use the hook_civimodels_displayCiviModelData function to pass in an associative array with your model data for display
- define a TPL file that contains the Smarty &amp; HTML you want to use to display your model's data

`hook_civimodels_displayCiviModelData` accepts two parameters: `$contact_id` and `$data`.

Use `$contact_id` to retrieve your model data for the relevant contact. Store the results of your model in an associative array that you _append_ to `$data`.

Ensure your data includes a key called `template`. The value for this key can be any Smarty/HTML you wish. If you are using Smarty, you can reference your model data via the Smarty variable `$model`.

### Example usage

The [CiviRFM model extension](https://github.com/australiangreens/civirfm) contains the following code:

```
function civirfm_civimodels_displayCiviModelData($contact_id, &$data) {
  if (!CRM_Core_Permission::check('access civirfm')) {
    return;
  }
  $contactRfm = \Civi\Api4\ContactRfm::get(FALSE)
  ->addSelect('id', 'contact_id', 'recency', 'frequency', 'monetary', 'date_calculated')
  ->addWhere('contact_id', '=', $contact_id)
  ->execute()
  ->first(); // we can safely assume there is only a single ContactRfm record per contact

  if (isset($contactRfm['date_calculated'])) {
    $civirfm = [
      'contact_id' => $contact_id,
      'recency' => $contactRfm['recency'],
      'frequency' => $contactRfm['frequency'],
      'monetary' => $contactRfm['monetary'],
      'date_calculated' => $contactRfm['date_calculated'],
      'rfm_time' => \Civi::settings()->get('civirfm_rfm_period'),
      'curr_symbol' => CRM_Core_Config::singleton()->defaultCurrencySymbol,
      'template' => 'CRM/Civirfm/Page/ContactRfm.tpl'
    ];
    $data['civirfm'] = $civirfm;
  }
}
```
The related template contains the following code (excerpt):
```
<div class='crm-content-block'>
  <h4>{ts}RFM fundraising information{/ts}</h4>
    {if isset($model.date_calculated)}
    {* We have RFM values to display *}
    <table class="report-layout" style="max-width: 500px;">
      <thead>
        <tr>
          <th colspan="2">The following fundraising data was calculated on {ts 1=$model.date_calc} %1{/ts}</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Last gift (recency)</td>
          <td>{ts 1=$model.recency} %1 days ago{/ts}</td>
        </tr>
```

## Known Issues
N/A

## License
This is an [extension for CiviCRM](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/), licensed under [AGPL-3.0](LICENSE.txt).

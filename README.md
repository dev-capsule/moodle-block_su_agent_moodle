![Agent Moodle](https://raw.githubusercontent.com/dev-capsule/assetAgentMoodle/ea41e8f78366fa16a95b7bd7235ecef4701f4875/icon.png)

# Moodle Agent

The Moodle agent was developed to allow a user to send their login information when encountering an issue. It does not require any tables in the Moodle database.

The Moodle agent has two modes of operation :

## Simple Mode

The simple mode enables the user to copy and paste their login information, which can then be shared, for example, through a hotline.

![Block simple](https://raw.githubusercontent.com/dev-capsule/assetAgentMoodle/cdec16370b9149eea424e8629f79f3b74219a3b6/Block_Simple.jpg)

To activate this mode, an administrator simply needs to uncheck the **mailenabled** box in the plugin settings.

![Simple settings](https://raw.githubusercontent.com/dev-capsule/assetAgentMoodle/cdec16370b9149eea424e8629f79f3b74219a3b6/Block_Simple_Settings.jpg)

## Advanced Mode

The advanced mode allows the user to describe their issue and send their information via email.

The plugin in this mode offers several features :
- **supportemail** : The administrator's email address will receive the user's information along with the description of the issue.
- **mailto** : It is possible to cc a second email address (such as a distribution list, for example).
- **subject** : It is possible to customize the email subject.

![Advanced settings](https://raw.githubusercontent.com/dev-capsule/assetAgentMoodle/cdec16370b9149eea424e8629f79f3b74219a3b6/Block_Advanced_Settings.jpg)

The user can send their information to the platform administrator via email with a single click from their dashboard.

![Block advanced](https://raw.githubusercontent.com/dev-capsule/assetAgentMoodle/cdec16370b9149eea424e8629f79f3b74219a3b6/Block_Advanced.jpg)

---

## Installation

1. Download the plugin files from this repository or the [Moodle Plugins Directory](https://moodle.org/plugins/block_su_agent_moodle).
2. Place the plugin folder into the appropriate directory of your Moodle installation:
   - For this plugin, place it in `/blocks/`.
3. Log in as an administrator on your Moodle site.
4. Go to **Site Administration > Plugins** and follow the steps to install the plugin.
5. Configure the settings as needed via **Site Administration > Plugins > Moodle Agent**.

Of course, the user will need to add it to their dashboard by enabling editing mode and adding it as a block.

### **Warning** Email sending must be configured on your Moodle platform.

---

## Compatibility

Tested with Moodle:
- Moodle **3.9.6+ (Build: 20210330)**
- Moodle **4.1.3 (Build: 20230424)**
- Moodle **4.3.2+ (Build: 20240125)**
- Moodle **4.5+ (Build: 20241122)**

- **PHP**: **7.2.34** to **8.3.14**.

---

## Support

If you encounter any issues or have suggestions:
- Open an **issue** on GitHub.

# Datacurso AI Provider for Moodle

The **Datacurso AI Provider** plugin enables seamless integration of Artificial Intelligence services in Moodle through a centralized token consumption system. It functions as an **AI provider** in the `ai/provider` directory and is designed to allow other compatible plugins to utilize AI services (text processing, image generation, summaries, etc.) without directly connecting to external APIs.

## Requirements

- **Moodle version**: 4.3+ (4.3, 4.4 recommended)
- **PHP version**: 8.0 or higher
- **Moodle AI subsystem**: Must be enabled
- **External service**: Access to a Datacurso-compatible token management API
- **Database**: MySQL 5.7+ or PostgreSQL 10+
- **Network**: Outbound HTTPS connections to AI service endpoints

## Key Features

### Centralized Token Management
- **License Key Integration**: Secure authentication with external AI services
- **API Base URL Configuration**: Flexible endpoint configuration for token consumption
- **Token Limit Monitoring**: Configurable thresholds with administrator alerts
- **Usage Tracking**: Comprehensive logging of all AI service consumption

### Administrative Dashboard
The plugin provides a comprehensive admin interface with three main sections:

1. **Token Consumption History**
   - Detailed logs of all AI service usage
   - Filtering by plugin and specific actions
   - Timestamp tracking for audit purposes

2. **General Reports**
   - Statistical graphs by service type and date ranges
   - Usage trends and patterns visualization
   - Performance metrics and success rates
   - Cost analysis and budget tracking

3. **Compatible Plugins Registry**
   - List of all plugins that can use this AI provider
   - Installation status indicators
   - Direct links to plugin repositories
   - Version compatibility information

### AI Action Management
- **Granular Control**: Enable/disable specific AI actions per service type
- **Service Categories**: Text processing, image generation, summarization, translation
- **Permission-based Access**: Role-based AI service availability
- **Rate Limiting**: Configurable usage limits per user/course

### Extensible Architecture
The plugin provides generic base classes for:
- **Token API Consumption**: Standardized token management interface
- **General AI Service Connection**: Common AI service communication layer
- **Plugin-specific AI Services**: Specialized connectors for individual use cases

Compatible plugins only need to instantiate the appropriate class and specify the endpoint route with required methods and parameters.

## Installation

### Method 1: Plugin Installer (Recommended)

1. **Login** as site administrator
2. **Navigate** to Site administration → Plugins → Install plugins
3. **Upload** the plugin ZIP file
4. **Review** the validation report
5. **Complete** the installation process

### Method 2: Manual Installation

1. **Extract** plugin files to:
   ```
   {moodleroot}/ai/provider/datacurso/
   ```

2. **Set permissions** (if needed):
   ```bash
   chmod -R 755 ai/provider/datacurso/
   chown -R www-data:www-data ai/provider/datacurso/
   ```

3. **Complete installation**:
   - **Web interface**: Site administration → Notifications
   - **Command line**: `php admin/cli/upgrade.php`

### Post-Installation Verification

1. **Verify AI subsystem** is enabled:
   - Site administration → AI → AI settings
   
2. **Check provider registration**:
   - Site administration → AI → AI providers
   - Confirm "Datacurso Provider" appears in the list

3. **Test basic connectivity** through the configuration page

## Configuration

### Initial Setup

Navigate to: **Site administration → Plugins → AI providers → Datacurso Provider**

#### Required Settings

| Setting | Description | Example |
|---------|-------------|---------|
| **License Key** | Your Datacurso service license key | `dc_lic_abc123...` |
| **API Base URL** | Token management API endpoint | `https://api.datacurso.com/v1/` |
| **Token Limit** | Alert threshold for token consumption | `10000` |

### AI Action Configuration

Enable or disable specific AI capabilities:

- ✅ **Text Generation**: Content creation and completion
- ✅ **Text Summarization**: Automatic content summarization  
- ✅ **Image Generation**: AI-powered image creation
- ✅ **Translation Services**: Multi-language translation
- ✅ **Sentiment Analysis**: Text emotion detection
- ⚠️ **Custom Actions**: Plugin-specific AI functions

### Access Management

Configure which user roles can access AI services:
- **Students**: Basic AI features (if enabled)
- **Teachers**: Full AI toolkit access
- **Managers**: Administrative controls + AI services
- **Site Administrators**: Complete system management

## Administrative Features

### Token Consumption Reports

Access detailed usage analytics through:
**Site administration → AI → Datacurso Provider → Token History**

Available filters:
- Date range selection
- Plugin-specific filtering  
- User-based reports
- Action type filtering
- Success/failure status

### Usage Statistics Dashboard

Visual analytics available through:
**Site administration → AI → Datacurso Provider → General Reports**

Chart types:
- **Usage Trends**: Token consumption over time
- **Plugin Distribution**: Usage by plugin
- **Action Breakdown**: Most popular AI services
- **User Activity**: Top consumers
- **Error Analysis**: Failed request patterns

### Compatible Plugins Management

Monitor ecosystem integration:
**Site administration → AI → Datacurso Provider → Compatible Plugins**

Features:
- Installation status tracking
- Repository link management  
- Version compatibility checking
- Bulk enable/disable operations

## Troubleshooting

### Common Issues

#### "AI provider not found" Error
**Cause**: Provider not properly registered
**Solution**:
1. Verify installation in `ai/provider/datacurso/`
2. Run `php admin/cli/upgrade.php`
3. Check AI subsystem is enabled

#### Token Limit Exceeded
**Cause**: Usage exceeded configured limit
**Solution**:
1. Check token consumption reports
2. Increase limit in settings
3. Purchase additional tokens from Datacurso

#### API Connection Errors
**Cause**: Network connectivity or authentication issues
**Solution**:
1. Verify License Key is correct
2. Check API Base URL configuration
3. Test network connectivity: `curl -I https://api.datacurso.com`
4. Review error logs for detailed information

#### Slow AI Response Times
**Cause**: High server load or network latency
**Solution**:
1. Check server resources
2. Contact Datacurso support for API status
3. Verify network connectivity
4. Review API endpoint performance

### Debug Information

Enable detailed logging through Moodle's debugging system:
1. **Moodle debugging**: Set to DEVELOPER level
2. **Log location**: `moodledata/temp/logs/`
3. **Log format**: Search for "datacurso_aiprovider"

## Security Considerations

### Data Privacy
- **Token logs**: Retrieved from external API, not stored locally
- **Request data**: May include sensitive content
- **GDPR compliance**: Data handling follows external service policies
- **Data retention**: Managed by Datacurso service

### API Security
- **License key**: Stored encrypted in Moodle database
- **HTTPS only**: All API communications use SSL/TLS
- **Rate limiting**: Prevents abuse and DoS attacks
- **Request validation**: Input sanitization and validation

### Access Control
- **Capability-based**: Uses Moodle's permission system
- **Role restrictions**: Configurable per AI action type
- **Audit logging**: Usage tracking via external API
- **IP restrictions**: Optional IP-based access control

## Multilingual Support

### Included Languages
- **English** (`lang/en/`)
- **Spanish** (`lang/es/`)

## Version History

### Version 1.0.0 (Current)
- Initial release
- Core AI provider functionality
- Token management system
- Administrative dashboard
- Compatible plugin registry
- HTTP client for AI services

### Planned Features (v1.1.0)
- Enhanced caching mechanisms
- Advanced usage analytics
- Plugin marketplace integration
- Mobile app compatibility
- Multi-tenant support

## Developer Documentation

### For Plugin Developers

To use the Datacurso AI Provider in your plugin, follow this simple pattern:

#### Basic Usage Example

```php
// Import the AI services API class
use aiprovider_datacurso\httpclient\ai_services_api;

// Prepare your data
$body = [
    'course' => $course->fullname,
    'activity' => $cm->name,
    'activity_type' => $cm->modname,
    'approvalpercent' => $approvalpercent,
    'comments' => $comments,
];

// Create client instance and make request
$client = new ai_services_api();
$response = $client->request('POST', '/rating/query', $body);
```

### Creating Compatible Plugins

To integrate your plugin with the Datacurso AI Provider:

1. **Add Plugin Dependency**: Include the provider as a dependency in your `version.php`
2. **Import the API Class**: Use the `aiprovider_datacurso\httpclient\ai_services_api` class
3. **Make Requests**: Instantiate the client and call the `request()` method with your endpoint and data
4. **Handle Responses**: Process the returned data according to your plugin's needs

### Best Practices

1. Always check for success before processing response data
2. Implement fallbacks when AI services are unavailable
3. Cache responses when appropriate to reduce token consumption
4. Validate input data before sending to AI services
5. Handle rate limits gracefully with user-friendly messages

## Support and Contributing

- **Author**: Developer <developer@datacurso.com>
- **Documentation**: [Moodle AI API Docs](https://moodledev.io/docs/apis/ai)
- **Issues**: Report through Moodle plugins directory
- **Contributing**: Follow Moodle coding standards
- **Community**: Join Moodle AI development forums

## License

This program is free software: you can redistribute it and/or modify it under the terms of the **GNU General Public License** as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but **WITHOUT ANY WARRANTY**; without even the implied warranty of **MERCHANTABILITY** or **FITNESS FOR A PARTICULAR PURPOSE**. See the [GNU General Public License v3](https://www.gnu.org/licenses/gpl-3.0.html) for more details.
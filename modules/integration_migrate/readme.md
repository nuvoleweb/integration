# How to
1. Creater a new migration_plus configuration in your config folder or paste in admin/config/development/configuration/single/import
```yaml
label: Migrate example 
migration_groups:
  - Integration migration 
source:
  plugin: integration_documents
  data_path: '/path/to/node.json'
destination:
  plugin: integration_node 
```
2. Run `drush mi --all` to run the migration

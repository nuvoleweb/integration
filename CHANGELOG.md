### Changelog

#### 7.x-1.x-dev

- 2016-06-09: Implementation of custom Rules module events which could be used during
              migration processes.
- 2016-05-27: Adding ```ProducerInterface::push()``` which will create the
              document and push it to the set backend in one shot.
- 2016-02-25: Adding support for Backend query arguments. Now backends can be queried
              by calling ```$backend->find('article', ['id' => 12345])```.
              Query arguments implementation are backend specific.
- 2016-01-08: Stand-alone Migrate components are moved into into a separate
              module called ```integration_migrate``` for more information check
              https://github.com/nuvoleweb/integration/issues/2#issuecomment-174941219
- 2016-01-08: Adding backend entities mapping table: remote backend IDs are stored
              locally in the ``integration_backend_entities`` table and exposed
              via Entity API.

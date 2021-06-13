## HEATMAP APP rest API
This is the heatmap app
### Instalation
> Requirements: git, docker, docker-compose

```
git clone git@github.com:mariuspop86/fluffy-palm-tree.git
cd fluffy-palm-tree
cp .env.dist .env
make
```
The app should be up and ready, you can access it on [localhost:8880](http://localhost:8880/api/doc)

### Api Doc [localhost:8880/api/doc](http://localhost:8880/api/doc)

### Running tests

To run tests run 
`cp .env.test .env.test.local` and update the `DATABASE_URL` env to 
`DATABASE_URL="mysql://heatmap:heatmap@db:3306/heatmap?serverVersion=5.7"`
then run `make run-test`


commandline_importcustomers module allows users to import customers from external files (CSV or JSON) formats 

- Installing module on app/code
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy -f

So After installing modules, you must attach your csv or json in PUB folder ( with  attached formats)
Then run command line 

php bin/magento customer:import --Format="sample-json" --File="sample.json"
php bin/magento customer:import --Format="sample-csv" --File="sample.csv"

Then check customer entity table will find new customer data imported

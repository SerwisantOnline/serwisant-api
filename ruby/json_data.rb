require 'oauth'
require 'json'

c = OAuth::Consumer.new('f073e5b4-e638-439a-8109-7da713cfd73e', '2bc1JjFYqGEqRVaK1RsHnbeiVtgnASHl', {:site => 'https://serwisant-online.pl', :signature_method => "HMAC-SHA1"})

result = c.request(:get, '/api/v1/orders/1425.json')

raise "HTTP request error - #{result.code}" if result.code.to_i != 200

order = JSON.parse(c.request(:get, '/api/v1/orders/1425.json').body)

raise "App request error #{order['errors']}" if order.key?('errors')

puts order.inspect


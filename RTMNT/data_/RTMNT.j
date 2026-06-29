#

# baseline current state
# we can take this out to 2060-70
# (+ 2026 43)2069  p > 95  g 95
# (+ 2026 38)2064  p 95  g 80
# (+ 2026 31)2057  p 80 g < 80

# fem 80% is 38 years, 95% is 43 years
# male 80% is 31 years, 95% is 37 years


commodity £1000.00  ; Optional: Customize how amounts are displayed
commodity $1,000.00
commodity €1.000,00
commodity ₹1,000.00

P 2026-01-01 €  $1.16
P 2026-01-01 £  $1.34
P 2026-01-01 $  ₹90


P 2026-01-09 €  $1.16
P 2026-01-09 £  $1.34
P 2026-01-09 $  ₹90.13

P 2026-06-26 €  $1.14
P 2026-06-26 £  $1.32
P 2026-06-26 $  ₹94.35


account assets
account assets:cash
account assets:hl
account assets:wellsfargo:2525
account assets:wellsfargo:8340
account assets:wellsfargo:5828
account assets:wellsfargo:4266
account assets:wellsfargo:1979
account assets:grodin:tod
account assets:grodin:irag
account assets:grodin:irap
account assets:grodin:rothg
account assets:grodin:rothp
account assets:ukpensg
account assets:ukpensp
account assets:closebrothers
account assets:hsbc
account assets:pnb
account assets:realestate


account equity

account revenue
account revenue:wagesp
account revenue:wagesg
account revenue:socsecp  ; 
account revenue:socsecg
account revenue:tkpensp
account revenue:tkpensg
account revenue:ukpensp
account revenue:ukpensg
account revenue:interest


account expense
account expense:household
account expense:rent
account expense:travel
account expense:misc

include socsecp.j
include socsecg.j
include tkpensp.j
include tkpensg.j
include ukpensp.j
include ukpensg.j

include irag.j
include irap.j

include realestate.j
include HL.j
include wf.j
include closebrothers.j



include expenses.j

2026-06-01  Init
    assets:cash			   -£30000
    equity

2026-06-01  Init
    assets:pnb			   -₹10000000
    equity





2026-06-01  Init
    assets:grodin:tod		    -$1218000
    equity

2026-06-01  Init
    assets:grodin:rothg		    -$332000
    equity

2026-06-01  Init
    assets:grodin:rothp		    -$62000
    equity

2026-06-01  Wages p 
    revenue:wagesp		    £38400
    assets:cash

2026-06-01  Wages g
    revenue:wagesg		    £98400
    assets:cash

2027-03-01  Wages g
    revenue:wagesg		    £74600
    assets:cash





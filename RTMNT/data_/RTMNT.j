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
account assets:HL
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
account assets:grodin:ukpensg
account assets:grodin:ukpensp
account assets:closebrothers:12532369
account assets:hsbc


account equity

account revenue
account revenue:wagesp
account revenue:wagesg
account revenue:socsecp
account revenue:socsecg
account revenue:tkpensp
account revenue:tkpensg


account expense
account expense:household
account expense:rent
account expense:travel
account expense:misc

include socsecp.j

2026-06-01  Init
    assets:closebrothers:12532369   -£117000
    equity

2026-06-01  Init
    assets:grodin:tod		    -$1218000
    equity

2026-06-01  Init
    assets:grodin:gira		    -$708000
    equity

2026-06-01  Init
    assets:grodin:pira		    -$578000
    equity

2026-06-01  Init
    assets:grodin:groth		    -$332000
    equity

2026-06-01  Init
    assets:grodin:proth		    -$62000
    equity





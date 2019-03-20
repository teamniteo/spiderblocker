workflow "Deploy" {
  resolves = ["Deploy Plugin"]
  on = "push"
}

# Filter for tag
action "tag" {
    uses = "actions/bin/filter@master"
    args = "tag"
}

action "Deploy Plugin" {
  needs = ["tag"]
  uses = "./.github/deploy-plugin"
  secrets = ["SVN_PASSWORD", "SVN_USERNAME"]
  env = {
    SLUG = "spiderblocker"
  }
}

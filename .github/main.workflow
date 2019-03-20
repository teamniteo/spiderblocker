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
  env = {
    SLUG = "spiderblocker"
  }
  secrets = ["SVN_USERNAME", "SVN_PASSWORD"]
}
